<?php

declare(strict_types=1);

namespace Tests\Integrational\EventSubscriber;

use ChamberOrchestra\DoctrineSlugBundle\EventSubscriber\SlugSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugSubscriberTest extends KernelTestCase
{
    public function testSubscriberIsAvailableInContainer(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        self::assertTrue($container->has(SlugSubscriber::class));
        self::assertInstanceOf(SlugSubscriber::class, $container->get(SlugSubscriber::class));
    }
}
