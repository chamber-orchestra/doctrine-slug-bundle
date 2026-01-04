<?php

declare(strict_types=1);

namespace Tests\Integrational\Slug\Generator;

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\GeneratorInterface;
use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GeneratorInterfaceTest extends KernelTestCase
{
    public function testGeneratorServiceImplementsInterface(): void
    {
        self::bootKernel();

        $generator = self::getContainer()->get(GeneratorInterface::class);

        self::assertInstanceOf(StringGenerator::class, $generator);
    }
}
