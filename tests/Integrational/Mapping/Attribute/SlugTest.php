<?php

declare(strict_types=1);

namespace Tests\Integrational\Mapping\Attribute;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugTest extends KernelTestCase
{
    public function testAttributeCanBeInstantiated(): void
    {
        self::bootKernel();

        $attr = new Slug(source: 'name', update: true, separator: '_');

        self::assertSame('name', $attr->source);
        self::assertTrue($attr->update);
        self::assertSame('_', $attr->separator);
    }
}
