<?php

declare(strict_types=1);

namespace Tests\Unit\Mapping\Attribute;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function testDefaults(): void
    {
        $attr = new Slug();

        self::assertNull($attr->source);
        self::assertFalse($attr->update);
        self::assertSame('-', $attr->separator);
    }

    public function testCustomValues(): void
    {
        $attr = new Slug(source: 'name', update: true, separator: '_');

        self::assertSame('name', $attr->source);
        self::assertTrue($attr->update);
        self::assertSame('_', $attr->separator);
    }
}
