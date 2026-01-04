<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\LogicException;
use PHPUnit\Framework\TestCase;

final class LogicExceptionTest extends TestCase
{
    public function testItImplementsExceptionInterface(): void
    {
        $exception = new LogicException('nope');

        self::assertInstanceOf(\LogicException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
