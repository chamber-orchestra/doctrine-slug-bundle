<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use ChamberOrchestra\DoctrineSlugBundle\Exception\MappingException;
use PHPUnit\Framework\TestCase;

final class MappingExceptionTest extends TestCase
{
    public function testNotNullableWithSourceNullableMessage(): void
    {
        $exception = MappingException::notNullableWithSourceNullable('App\\Entity\\Post', 'title', 'slug');

        self::assertSame(
            'Source property "title" of class "App\\Entity\\Post" is nullable while sluggable property "slug" not.',
            $exception->getMessage()
        );
        self::assertInstanceOf(\ChamberOrchestra\MetadataBundle\Exception\MappingException::class, $exception);
    }

    public function testNotUniqueMessage(): void
    {
        $exception = MappingException::notUnique('App\\Entity\\Post', 'slug');

        self::assertSame(
            'Property "slug" of class "App\\Entity\\Post" must be unique.',
            $exception->getMessage()
        );
    }
}
