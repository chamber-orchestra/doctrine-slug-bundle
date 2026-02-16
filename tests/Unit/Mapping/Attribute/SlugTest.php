<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Mapping\Attribute;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function testDefaults(): void
    {
        $attr = new Slug(source: 'title');

        self::assertSame('title', $attr->source);
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

    public function testEmptySourceThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "source" option of #[Slug] must be a non-empty string.');

        new Slug(source: '');
    }

    public function testEmptySeparatorThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "separator" option of #[Slug] must be exactly one character');

        new Slug(source: 'name', separator: '');
    }

    public function testMultiCharSeparatorThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "separator" option of #[Slug] must be exactly one character, got "--".');

        new Slug(source: 'name', separator: '--');
    }
}
