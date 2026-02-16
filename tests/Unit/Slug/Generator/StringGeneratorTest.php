<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Slug\Generator;

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

final class StringGeneratorTest extends TestCase
{
    public function testItLowercasesSluggedString(): void
    {
        $slugger = $this->createMock(SluggerInterface::class);
        $slugger
            ->expects(self::once())
            ->method('slug')
            ->with('Hello World', '-')
            ->willReturn(new UnicodeString('Hello-World'));

        $generator = new StringGenerator($slugger);

        self::assertSame('hello-world', $generator->generate('Hello World'));
    }

    public function testItHonorsCustomDelimiter(): void
    {
        $slugger = $this->createMock(SluggerInterface::class);
        $slugger
            ->expects(self::once())
            ->method('slug')
            ->with('Hello World', '_')
            ->willReturn(new UnicodeString('Hello_World'));

        $generator = new StringGenerator($slugger);

        self::assertSame('hello_world', $generator->generate('Hello World', '_'));
    }

    public function testItTruncatesWhenLengthIsProvided(): void
    {
        $slugger = $this->createMock(SluggerInterface::class);
        $slugger
            ->expects(self::once())
            ->method('slug')
            ->with('Hello World', '-')
            ->willReturn(new UnicodeString('Hello-World-Again'));

        $generator = new StringGenerator($slugger);

        self::assertSame('hello-world', $generator->generate('Hello World', '-', 11));
    }
}
