<?php

declare(strict_types=1);

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
