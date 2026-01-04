<?php

declare(strict_types=1);

namespace Tests\Integrational\DependencyInjection;

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ChamberOrchestraDoctrineSlugExtensionTest extends KernelTestCase
{
    public function testContainerExposesGeneratorAlias(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        self::assertTrue($container->has(GeneratorInterface::class));
        self::assertInstanceOf(StringGenerator::class, $container->get(GeneratorInterface::class));
    }
}
