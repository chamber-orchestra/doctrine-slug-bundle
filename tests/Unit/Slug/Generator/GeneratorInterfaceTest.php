<?php

declare(strict_types=1);

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
