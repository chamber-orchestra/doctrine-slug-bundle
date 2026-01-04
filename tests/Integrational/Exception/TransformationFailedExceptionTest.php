<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use ChamberOrchestra\DoctrineSlugBundle\Exception\TransformationFailedException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TransformationFailedExceptionTest extends KernelTestCase
{
    public function testExceptionWorksInKernelContext(): void
    {
        self::bootKernel();

        $exception = new TransformationFailedException('nope');

        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
