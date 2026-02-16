<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Integrational;

use ChamberOrchestra\DoctrineSlugBundle\ChamberOrchestraDoctrineSlugBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ChamberOrchestraDoctrineSlugBundleTest extends KernelTestCase
{
    public function testBundleIsRegistered(): void
    {
        self::bootKernel();

        $bundles = self::$kernel->getBundles();
        $found = false;
        foreach ($bundles as $bundle) {
            if ($bundle instanceof ChamberOrchestraDoctrineSlugBundle) {
                $found = true;
                break;
            }
        }

        self::assertTrue($found);
    }
}
