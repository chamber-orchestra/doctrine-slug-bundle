<?php

declare(strict_types=1);

namespace Tests\Integrational\Mapping\Configuration;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugConfigurationTest extends KernelTestCase
{
    public function testConfigurationCanBeUsedWithKernelBooted(): void
    {
        self::bootKernel();

        $config = new SlugConfiguration();
        $config->mapField('slug', ['slug' => true, 'source' => 'name']);

        $sluggable = $config->getSluggableFields();

        self::assertArrayHasKey('slug', $sluggable);
        self::assertSame('name', $sluggable['slug']['source']);
    }
}
