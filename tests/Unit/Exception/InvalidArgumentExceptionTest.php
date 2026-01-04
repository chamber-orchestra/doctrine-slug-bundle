<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class InvalidArgumentExceptionTest extends TestCase
{
    public function testItImplementsExceptionInterface(): void
    {
        $exception = new InvalidArgumentException('nope');

        self::assertInstanceOf(\InvalidArgumentException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
