<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class InvalidArgumentExceptionTest extends KernelTestCase
{
    public function testExceptionWorksInKernelContext(): void
    {
        self::bootKernel();

        $exception = new InvalidArgumentException('nope');

        self::assertInstanceOf(\InvalidArgumentException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
