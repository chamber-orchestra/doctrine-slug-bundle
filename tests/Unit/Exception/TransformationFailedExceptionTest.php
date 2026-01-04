<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\TransformationFailedException;
use PHPUnit\Framework\TestCase;

final class TransformationFailedExceptionTest extends TestCase
{
    public function testItImplementsExceptionInterface(): void
    {
        $exception = new TransformationFailedException('nope');

        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
