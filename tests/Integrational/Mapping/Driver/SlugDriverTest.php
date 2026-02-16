<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Integrational\Mapping\Driver;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Driver\SlugDriver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugDriverTest extends KernelTestCase
{
    public function testDriverIsAvailableInContainer(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        self::assertTrue($container->has(SlugDriver::class));
        self::assertInstanceOf(SlugDriver::class, $container->get(SlugDriver::class));
    }
}
