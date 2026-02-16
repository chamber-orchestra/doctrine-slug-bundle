<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\DependencyInjection;

use ChamberOrchestra\DoctrineSlugBundle\DependencyInjection\ChamberOrchestraDoctrineSlugExtension;
use ChamberOrchestra\DoctrineSlugBundle\EventSubscriber\SlugSubscriber;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ChamberOrchestraDoctrineSlugExtensionTest extends TestCase
{
    public function testItLoadsServiceConfiguration(): void
    {
        $container = new ContainerBuilder();
        $extension = new ChamberOrchestraDoctrineSlugExtension();

        $extension->load([], $container);

        self::assertTrue($container->hasDefinition(StringGenerator::class));
        self::assertTrue($container->hasAlias(GeneratorInterface::class));
        self::assertSame(
            StringGenerator::class,
            (string)$container->getAlias(GeneratorInterface::class)
        );
        self::assertTrue($container->hasDefinition(SlugSubscriber::class));
    }
}
