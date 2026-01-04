<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->set(StringGenerator::class);
    $services->alias(GeneratorInterface::class, StringGenerator::class);

    $services->load('ChamberOrchestra\\DoctrineSlugBundle\\', __DIR__.'/../../*')
        ->exclude(__DIR__.'/../../{DependencyInjection,Resources,ExceptionInterface,Repository}');

    $services->load('ChamberOrchestra\\DoctrineSlugBundle\\EventSubscriber\\', __DIR__.'/../../EventSubscriber/')
        ->tag('doctrine.event_subscriber');


    $services->load('ChamberOrchestra\\DoctrineSlugBundle\\Slug\\Generator\\', __DIR__.'/../../Slug/Generator/')
        ->lazy(true);
};
