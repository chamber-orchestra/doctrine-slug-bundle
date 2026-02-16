<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
