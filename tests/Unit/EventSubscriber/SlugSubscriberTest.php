<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\EventSubscriber;

use ChamberOrchestra\DoctrineSlugBundle\EventSubscriber\SlugSubscriber;
use ChamberOrchestra\DoctrineSlugBundle\Exception\RuntimeException;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
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

        $em = $this->createEntityManagerWithSlugs([
            ['slug' => 'hello'],
            ['slug' => 'hello-1'],
        ]);

        $result = $this->callMakeUniqueSlug(
            $subscriber,
            $em,
            new SlugSubscriberEntity(),
            ['fieldName' => 'slug', 'length' => 10, 'separator' => '-'],
            'hello'
        );

        self::assertSame('hello-2', $result);
    }

    public function testMakeUniqueSlugThrowsOnMaxIterations(): void
    {
        $subscriber = new SlugSubscriber($this->createStub(GeneratorInterface::class));

        // Build 1001 existing slugs: a, a-1, a-2, ..., a-1000
        $existingSlugs = [['slug' => 'a']];
        for ($i = 1; $i <= 1000; $i++) {
            $existingSlugs[] = ['slug' => 'a-' . $i];
        }

        $em = $this->createEntityManagerWithSlugs($existingSlugs);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not generate a unique slug for "a" after 1000 attempts.');

        $this->callMakeUniqueSlug(
            $subscriber,
            $em,
            new SlugSubscriberEntity(),
            ['fieldName' => 'slug', 'length' => 255, 'separator' => '-'],
            'a'
        );
    }

    public function testMakeUniqueSlugReturnsPreferredWhenNoConflict(): void
    {
        $subscriber = new SlugSubscriber($this->createStub(GeneratorInterface::class));

        $em = $this->createEntityManagerWithSlugs([]);

        $result = $this->callMakeUniqueSlug(
            $subscriber,
            $em,
            new SlugSubscriberEntity(),
            ['fieldName' => 'slug', 'length' => 255, 'separator' => '-'],
            'hello-world'
        );

        self::assertSame('hello-world', $result);
    }

    private function createEntityManagerWithSlugs(array $slugRows): EntityManagerInterface
    {
        $query = $this->createStub(Query::class);
        $query->method('useQueryCache')->willReturnSelf();
        $query->method('getArrayResult')->willReturn($slugRows);

        $expr = $this->createStub(Expr::class);
        $expr->method('like')->willReturn($this->createStub(Expr\Comparison::class));

        $queryBuilder = $this->createStub(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('expr')->willReturn($expr);
        $queryBuilder->method('getQuery')->willReturn($query);

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('createQueryBuilder')->willReturn($queryBuilder);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repository);

        return $em;
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

final class SlugSubscriberEntity {}
