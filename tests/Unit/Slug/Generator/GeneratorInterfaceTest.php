<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Slug\Generator;

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use PHPUnit\Framework\TestCase;

final class GeneratorInterfaceTest extends TestCase
{
    public function testItDefinesGenerateMethod(): void
    {
        $reflection = new \ReflectionClass(GeneratorInterface::class);

        self::assertTrue($reflection->isInterface());
        self::assertTrue($reflection->hasMethod('generate'));
    }
}
