<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\EventSubscriber;

use ChamberOrchestra\DoctrineSlugBundle\Exception\RuntimeException;
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

/**
 * @phpstan-import-type SlugMapping from SlugConfiguration
 */
#[AsDoctrineListener(event: Events::onFlush)]
#[AsDoctrineListener(event: Events::postFlush)]
class SlugSubscriber extends AbstractDoctrineListener
{
    /** @var array<string, array<string, list<string>>> */
    private array $persisted = [];

    public function __construct(
        private readonly GeneratorInterface $generator,
    ) {
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();

        $changes = \array_merge(
            $this->getScheduledEntityUpdates($em, $class = SlugConfiguration::class),
            $this->getScheduledEntityInsertions($em, $class)
        );

        foreach ($changes as $change) {
            $this->process($change);
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
        $meta = $args->getClassMetadata();
        $config = $args->configuration;

        $uow = $em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($entity);

        $needRecomputeChangeSet = false;
        \assert($config instanceof SlugConfiguration);
        foreach ($config->getSluggableFields() as $field => $mapping) {
            // use changed value first
            if (!isset($changeSet[$mapping['source']])) {
                continue;
            }

            // pass manual changes for field
            /** @var array{0: mixed, 1: mixed} $fieldChange */
            $fieldChange = $changeSet[$field] ?? [null, null];
            [$old, $new] = $fieldChange;
            if (null !== $old && $old !== $new) {
                continue;
            }

            /** @var array{0: mixed, 1: mixed} $sourceChange */
            $sourceChange = $changeSet[$mapping['source']];
            [$old, $new] = $sourceChange;
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

    /**
     * @param SlugMapping $mapping
     */
    private function generate(EntityManagerInterface $em, object $entity, array $mapping): string
    {
        $meta = $em->getClassMetadata(ClassUtils::getClass($entity));
        $raw = $meta->getFieldValue($entity, $mapping['source']);
        $value = \is_string($raw) ? $raw : (string) ($raw instanceof \Stringable ? $raw : '');
        $slug = $this->generator->generate($value, $mapping['separator'], $mapping['length']);
        $slug = $this->makeUniqueSlug($em, $entity, $mapping, $slug);

        return $slug;
    }

    /**
     * @param SlugMapping $mapping
     */
    private function makeUniqueSlug(EntityManagerInterface $em, object $entity, array $mapping, string $preferredSlug): string
    {
        $fieldName = $mapping['fieldName'];

        /** @var EntityRepository<object> $er */
        $er = $em->getRepository($class = ClassUtils::getClass($entity));
        $qb = $er->createQueryBuilder('n');
        $escapedSlug = \str_replace(['%', '_'], ['\\%', '\\_'], $preferredSlug);
        $qb->select('n.'.$fieldName.' as slug')
            ->where($qb->expr()->like('n.'.$fieldName, ':prefix'))
            ->setParameter('prefix', $escapedSlug.'%');
        $result = $qb->getQuery()->useQueryCache(false)->getArrayResult();

        $persisted = $this->persisted[$class] ?? [];
        $persistedSlugs = $persisted[$fieldName] ?? [];
        $prefix = $preferredSlug;
        $persistedSlugs = \array_filter($persistedSlugs, static fn (string $s): bool => \str_starts_with($s, $prefix));
        /** @var list<string> $slugs */
        $slugs = \array_merge($persistedSlugs, \array_column($result, 'slug'));

        if (!\count($slugs)) {
            return $preferredSlug;
        }

        $slugMap = \array_flip($slugs);
        $i = 0;
        $maxIterations = 1000;
        $length = $mapping['length'];
        $slug = $preferredSlug;
        while (isset($slugMap[$slug])) {
            if ($i >= $maxIterations) {
                throw new RuntimeException(\sprintf('Could not generate a unique slug for "%s" after %d attempts.', $preferredSlug, $maxIterations));
            }

            $suffix = $mapping['separator'].++$i;

            if (null !== $length) {
                $len = \mb_strlen($preferredSlug);
                $excess = (int) \max(0, $len + \mb_strlen($suffix) - $length);
                $slug = \mb_substr($preferredSlug, 0, $len - $excess).$suffix;
            } else {
                $slug = $preferredSlug.$suffix;
            }
        }

        return $slug;
    }

    private function setFieldValue(EntityManagerInterface $em, object $entity, string $fieldName, ?string $slug): void
    {
        $meta = $em->getClassMetadata($class = ClassUtils::getClass($entity));
        $meta->setFieldValue($entity, $fieldName, $slug);

        if (null !== $slug) {
            $this->persisted[$class][$fieldName][] = $slug;
        }
    }
}
