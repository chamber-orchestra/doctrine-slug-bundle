<?php

declare(strict_types=1);

namespace Tests\Unit\Contracts\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Contracts\Entity\SlugInterface;
use PHPUnit\Framework\TestCase;

final class SlugInterfaceTest extends TestCase
{
    public function testItDefinesContract(): void
    {
        $reflection = new \ReflectionClass(SlugInterface::class);

        self::assertTrue($reflection->isInterface());
        self::assertTrue($reflection->hasMethod('getName'));
        self::assertTrue($reflection->hasMethod('getSlug'));
    }
}
