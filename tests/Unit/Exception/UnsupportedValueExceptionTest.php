<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\UnsupportedValueException;
use PHPUnit\Framework\TestCase;

final class UnsupportedValueExceptionTest extends TestCase
{
    public function testItImplementsExceptionInterface(): void
    {
        $exception = new UnsupportedValueException('nope');

        self::assertInstanceOf(\InvalidArgumentException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
