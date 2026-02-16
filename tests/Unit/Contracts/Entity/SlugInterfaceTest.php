<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Contracts\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Contracts\Entity\SlugInterface;
use PHPUnit\Framework\TestCase;

final class SlugInterfaceTest extends TestCase
{
    public function testItDefinesContract(): void
    {
        $reflection = new \ReflectionClass(SlugInterface::class);

        self::assertTrue($reflection->isInterface());
        self::assertTrue($reflection->hasMethod('getName'));
        self::assertTrue($reflection->hasMethod('setName'));
        self::assertTrue($reflection->hasMethod('getSlug'));
    }
}
