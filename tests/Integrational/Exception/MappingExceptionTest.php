<?php

declare(strict_types=1);

namespace Tests\Integrational\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\MappingException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MappingExceptionTest extends KernelTestCase
{
    public function testCustomMessageHelpersAreAvailable(): void
    {
        self::bootKernel();

        $exception = MappingException::notUnique('App\\Entity\\Post', 'slug');

        self::assertSame('Property "slug" of class "App\\Entity\\Post" must be unique.', $exception->getMessage());
    }
}
