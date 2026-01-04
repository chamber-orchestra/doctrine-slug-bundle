<?php

declare(strict_types=1);

namespace Tests\Unit\Mapping\Driver;

use ChamberOrchestra\DoctrineSlugBundle\Exception\MappingException;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Driver\SlugDriver;
use ChamberOrchestra\MetadataBundle\Mapping\ORM\ExtensionMetadata;
use ChamberOrchestra\MetadataBundle\Reader\AttributeReader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use PHPUnit\Framework\TestCase;

final class SlugDriverTest extends TestCase
{
    public function testItMapsSlugMetadata(): void
    {
        $metadata = $this->createMetadata(SlugDriverEntity::class);
        $extension = new ExtensionMetadata($metadata);
        $driver = new SlugDriver(new AttributeReader());

        $driver->loadMetadataForClass($extension);

        $config = $extension->getConfiguration(SlugConfiguration::class);
        self::assertNotNull($config);

        $mapping = $config->getMapping('slug');
        self::assertSame('name', $mapping['source']);
        self::assertSame('-', $mapping['separator']);
        self::assertSame(255, $mapping['length']);
        self::assertFalse($mapping['nullable']);
    }

    public function testItRequiresSourceProperty(): void
    {
        $metadata = $this->createMetadata(SlugDriverMissingSourceEntity::class);
        $extension = new ExtensionMetadata($metadata);
        $driver = new SlugDriver(new AttributeReader());

        $this->expectException(\ChamberOrchestra\MetadataBundle\Exception\MappingException::class);
        $this->expectExceptionMessage('Class "Tests\\Unit\\Mapping\\Driver\\SlugDriverMissingSourceEntity" has no property "missing" specified in property "slug"');

        $driver->loadMetadataForClass($extension);
    }

    public function testItRequiresUniqueSlugColumn(): void
    {
        $metadata = $this->createMetadata(SlugDriverNotUniqueEntity::class);
        $extension = new ExtensionMetadata($metadata);
        $driver = new SlugDriver(new AttributeReader());

        $this->expectException(MappingException::class);
        $this->expectExceptionMessage('Property "slug" of class "Tests\\Unit\\Mapping\\Driver\\SlugDriverNotUniqueEntity" must be unique.');

        $driver->loadMetadataForClass($extension);
    }

    public function testItValidatesNullableSourceWithNotNullableSlug(): void
    {
        $metadata = $this->createMetadata(SlugDriverNullableSourceEntity::class);
        $extension = new ExtensionMetadata($metadata);
        $driver = new SlugDriver(new AttributeReader());

        $this->expectException(MappingException::class);
        $this->expectExceptionMessage('Source property "name" of class "Tests\\Unit\\Mapping\\Driver\\SlugDriverNullableSourceEntity" is nullable while sluggable property "slug" not.');

        $driver->loadMetadataForClass($extension);
    }

    private function createMetadata(string $className): ClassMetadata
    {
        $metadata = new ClassMetadata($className);
        $metadata->initializeReflection(new RuntimeReflectionService());

        return $metadata;
    }
}

final class SlugDriverEntity
{
    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Slug(source: 'name')]
    public string $slug = '';

    #[Column(type: 'string', length: 255, nullable: false)]
    public string $name = '';
}

final class SlugDriverMissingSourceEntity
{
    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Slug(source: 'missing')]
    public string $slug = '';
}

final class SlugDriverNotUniqueEntity
{
    #[Column(type: 'string', length: 255, unique: false, nullable: false)]
    #[Slug(source: 'name')]
    public string $slug = '';

    #[Column(type: 'string', length: 255, nullable: false)]
    public string $name = '';
}

final class SlugDriverNullableSourceEntity
{
    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Slug(source: 'name')]
    public string $slug = '';

    #[Column(type: 'string', length: 255, nullable: true)]
    public ?string $name = null;
}
