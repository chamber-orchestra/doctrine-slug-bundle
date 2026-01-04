<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

final class RuntimeExceptionTest extends TestCase
{
    public function testItImplementsExceptionInterface(): void
    {
        $exception = new RuntimeException('nope');

        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
