<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\UnsupportedValueException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UnsupportedValueExceptionTest extends KernelTestCase
{
    public function testExceptionWorksInKernelContext(): void
    {
        self::bootKernel();

        $exception = new UnsupportedValueException('nope');

        self::assertInstanceOf(\InvalidArgumentException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
