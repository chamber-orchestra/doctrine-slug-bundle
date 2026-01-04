<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Integrational;

use ChamberOrchestra\DoctrineSlugBundle\ChamberOrchestraDoctrineSlugBundle;
use ChamberOrchestra\MetadataBundle\ChamberOrchestraMetadataBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new ChamberOrchestraMetadataBundle(),
            new ChamberOrchestraDoctrineSlugBundle(),
            new DoctrineBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'test_secret',
            'test' => true,
        ]);
        $container->extension('doctrine', [
            'dbal' => [
                'url' => 'sqlite:///:memory:',
            ],
            'orm' => [
                'mappings' => [
                    'Tests' => [
                        'type' => 'attribute',
                        'dir' => __DIR__.'/Fixtures/Entity',
                        'prefix' => 'Tests\\Integrational\\Fixtures\\Entity',
                        'is_bundle' => false,
                    ],
                ],
            ],
        ]);
        $container->extension('chamber_orchestra_metadata', []);
        $container->extension('chamber_orchestra_doctrine_slug', []);
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__, 2);
    }
}
