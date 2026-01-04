<?php

declare(strict_types=1);

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
