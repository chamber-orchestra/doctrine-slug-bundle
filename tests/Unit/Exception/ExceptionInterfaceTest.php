<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use PHPUnit\Framework\TestCase;

final class ExceptionInterfaceTest extends TestCase
{
    public function testItIsAnInterface(): void
    {
        $reflection = new \ReflectionClass(ExceptionInterface::class);

        self::assertTrue($reflection->isInterface());
    }
}
