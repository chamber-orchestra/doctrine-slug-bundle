<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
