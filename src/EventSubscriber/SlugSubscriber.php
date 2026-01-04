<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\EventSubscriber;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use ChamberOrchestra\MetadataBundle\EventSubscriber\AbstractDoctrineListener;
use ChamberOrchestra\MetadataBundle\Helper\MetadataArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::onFlush)]
#[AsDoctrineListener(event: Events::postFlush)]
class SlugSubscriber extends AbstractDoctrineListener
{
    private array $persisted = [];

    public function __construct(
        private readonly GeneratorInterface $generator,
    )
    {
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();

        $changes = \array_merge(
            $this->getScheduledEntityUpdates($em, $class = SlugConfiguration::class),
            $this->getScheduledEntityInsertions($em, $class)
        );

        foreach ($changes as $args) {
            $this->process($args);
        }
    }

    public function postFlush(): void
    {
        $this->persisted = [];
    }

    private function process(MetadataArgs $args): void
    {
        $em = $args->entityManager;
        $entity = $args->entity;
        $meta = $args->classMetadata;
        $config = $args->configuration;

        $uow = $em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($entity);

        $needRecomputeChangeSet = false;
        foreach ($config->getSluggableFields() as $field => $mapping) {
            // use changed value first
            if (!isset($changeSet[$mapping['source']])) {
                continue;
            }

            // pass manual changes for field
            [$old, $new] = $changeSet[$field] ?? [null, null];
            if (null !== $old && $old !== $new) {
                continue;
            }

            [$old, $new] = $changeSet[$mapping['source']];
            if (null !== $old && false === $mapping['update']) {
                continue;
            }

            $needRecomputeChangeSet = true;
            if (null === $new) {
                $this->setFieldValue($em, $entity, $mapping['fieldName'], null);
                continue;
            }

            $this->setFieldValue($em, $entity, $mapping['fieldName'], $this->generate($em, $entity, $mapping));
        }

        if ($needRecomputeChangeSet) {
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    private function generate(EntityManagerInterface $em, object $entity, array $mapping): string
    {
        $meta = $em->getClassMetadata($class = ClassUtils::getClass($entity));
        $value = (string)$meta->getFieldValue($entity, $mapping['source']);
        $slug = $this->generator->generate($value, $mapping['separator'], $mapping['length']);
        $slug = $this->makeUniqueSlug($em, $entity, $mapping, $slug);

        return $slug;
    }

    private function makeUniqueSlug(EntityManagerInterface $em, object $entity, array $mapping, string $preferredSlug): string
    {
        /** @var EntityRepository $er */
        $er = $em->getRepository($class = ClassUtils::getClass($entity));
        $qb = $er->createQueryBuilder('n');
        $qb->select('n.' . $mapping['fieldName'] . ' as slug');
        $result = $qb->getQuery()->useQueryCache(true)->getArrayResult();

        $persisted = $this->persisted[$class] ?? [];
        $slugs = $persisted[$mapping['fieldName']] ?? [];
        $slugs = \array_merge($slugs, \array_column($result, 'slug'));

        if (!\count($slugs)) {
            return $preferredSlug;
        }

        $i = 0;
        $length = $mapping['length'];
        $slug = $preferredSlug;
        while (\in_array($slug, $slugs)) {
            $suffix = $mapping['separator'] . ++$i;
            $excess = \max(0, ($len = \mb_strlen($preferredSlug)) + mb_strlen($suffix) - $length);
            $slug = \mb_substr($preferredSlug, 0, $len - $excess) . $suffix;
        }

        return $slug;
    }

    private function setFieldValue(EntityManagerInterface $em, object $entity, string $fieldName, string|null $slug): void
    {
        $meta = $em->getClassMetadata($class = ClassUtils::getClass($entity));
        $meta->setFieldValue($entity, $fieldName, $slug);

        if (null !== $slug) {
            $this->persisted[$class][$fieldName][] = $slug;
        }
    }
}
