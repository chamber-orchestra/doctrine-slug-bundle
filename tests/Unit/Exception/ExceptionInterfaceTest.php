<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\ExceptionInterface;
use PHPUnit\Framework\TestCase;

final class ExceptionInterfaceTest extends TestCase
{
    public function testItIsAnInterface(): void
    {
        $reflection = new \ReflectionClass(ExceptionInterface::class);

        self::assertTrue($reflection->isInterface());
    }
}
