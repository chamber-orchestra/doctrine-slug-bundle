<?php

declare(strict_types=1);

namespace Tests\Unit\EventSubscriber;

use ChamberOrchestra\DoctrineSlugBundle\EventSubscriber\SlugSubscriber;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class SlugSubscriberTest extends TestCase
{
    public function testPostFlushClearsPersistedSlugs(): void
    {
        $subscriber = new SlugSubscriber($this->createStub(GeneratorInterface::class));
        $this->setPersisted($subscriber, ['Class' => ['slug' => ['hello']]]);

        $subscriber->postFlush();

        self::assertSame([], $this->getPersisted($subscriber));
    }

    public function testMakeUniqueSlugAppendsSuffixes(): void
    {
        $subscriber = new SlugSubscriber($this->createStub(GeneratorInterface::class));
        $this->setPersisted($subscriber, [SlugSubscriberEntity::class => ['slug' => ['hello']]]);

        $query = $this->createStub(Query::class);
        $query->method('useQueryCache')->with(true)->willReturnSelf();
        $query->method('getArrayResult')->willReturn([
            ['slug' => 'hello'],
            ['slug' => 'hello-1'],
        ]);

        $queryBuilder = $this->createStub(QueryBuilder::class);
        $queryBuilder->method('select')->with('n.slug as slug')->willReturnSelf();
        $queryBuilder->method('getQuery')->willReturn($query);

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('createQueryBuilder')->with('n')->willReturn($queryBuilder);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repository);

        $result = $this->callMakeUniqueSlug(
            $subscriber,
            $em,
            new SlugSubscriberEntity(),
            ['fieldName' => 'slug', 'length' => 10, 'separator' => '-'],
            'hello'
        );

        self::assertSame('hello-2', $result);
    }

    private function setPersisted(SlugSubscriber $subscriber, array $value): void
    {
        $setter = \Closure::bind(static function (SlugSubscriber $subscriber, array $value): void {
            $subscriber->persisted = $value;
        }, null, SlugSubscriber::class);

        $setter($subscriber, $value);
    }

    private function getPersisted(SlugSubscriber $subscriber): array
    {
        $getter = \Closure::bind(static function (SlugSubscriber $subscriber): array {
            return $subscriber->persisted;
        }, null, SlugSubscriber::class);

        return $getter($subscriber);
    }

    private function callMakeUniqueSlug(
        SlugSubscriber $subscriber,
        EntityManagerInterface $em,
        object $entity,
        array $mapping,
        string $preferredSlug
    ): string {
        $caller = \Closure::bind(static function (
            SlugSubscriber $subscriber,
            EntityManagerInterface $em,
            object $entity,
            array $mapping,
            string $preferredSlug
        ): string {
            return $subscriber->makeUniqueSlug($em, $entity, $mapping, $preferredSlug);
        }, null, SlugSubscriber::class);

        return $caller($subscriber, $em, $entity, $mapping, $preferredSlug);
    }
}

final class SlugSubscriberEntity
{
}
