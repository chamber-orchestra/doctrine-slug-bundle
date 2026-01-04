<?php

declare(strict_types=1);

namespace Tests\Integrational\Slug\Generator;

use ChamberOrchestra\DoctrineSlugBundle\Slug\Generator\StringGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class StringGeneratorTest extends KernelTestCase
{
    public function testGeneratorUsesSluggerService(): void
    {
        self::bootKernel();

        $generator = self::getContainer()->get(StringGenerator::class);

        self::assertSame('hello-world', $generator->generate('Hello World'));
    }
}
