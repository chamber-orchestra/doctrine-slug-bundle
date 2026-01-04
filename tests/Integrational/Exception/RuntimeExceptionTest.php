<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class RuntimeExceptionTest extends KernelTestCase
{
    public function testExceptionWorksInKernelContext(): void
    {
        self::bootKernel();

        $exception = new RuntimeException('nope');

        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
