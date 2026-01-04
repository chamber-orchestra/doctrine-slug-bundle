<?php

declare(strict_types=1);

namespace Tests\Unit\Mapping\Configuration;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use PHPUnit\Framework\TestCase;

final class SlugConfigurationTest extends TestCase
{
    public function testItFiltersSluggableMappings(): void
    {
        $config = new SlugConfiguration();
        $config->mapField('slug', ['slug' => true, 'source' => 'name']);
        $config->mapField('name', ['slug' => false]);

        $sluggable = $config->getSluggableFields();

        self::assertArrayHasKey('slug', $sluggable);
        self::assertArrayNotHasKey('name', $sluggable);
        self::assertSame('name', $sluggable['slug']['source']);
    }
}
