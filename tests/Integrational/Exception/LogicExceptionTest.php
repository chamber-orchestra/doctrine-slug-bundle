<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LogicExceptionTest extends KernelTestCase
{
    public function testExceptionWorksInKernelContext(): void
    {
        self::bootKernel();

        $exception = new LogicException('nope');

        self::assertInstanceOf(\LogicException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
