<?php

declare(strict_types=1);

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
