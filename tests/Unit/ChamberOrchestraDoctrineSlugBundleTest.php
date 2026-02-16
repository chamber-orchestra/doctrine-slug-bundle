<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use ChamberOrchestra\DoctrineSlugBundle\ChamberOrchestraDoctrineSlugBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ChamberOrchestraDoctrineSlugBundleTest extends TestCase
{
    public function testItExtendsSymfonyBundle(): void
    {
        $bundle = new ChamberOrchestraDoctrineSlugBundle();

        self::assertInstanceOf(Bundle::class, $bundle);
    }
}
