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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ExceptionInterfaceTest extends KernelTestCase
{
    public function testInterfaceIsAvailable(): void
    {
        self::bootKernel();

        $reflection = new \ReflectionClass(ExceptionInterface::class);

        self::assertTrue($reflection->isInterface());
    }
}
