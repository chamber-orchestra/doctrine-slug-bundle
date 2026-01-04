<?php

declare(strict_types=1);

namespace Tests\Integrational\Mapping\Driver;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Driver\SlugDriver;
use ChamberOrchestra\MetadataBundle\Mapping\ORM\ExtensionMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Integrational\Fixtures\Entity\Post;

final class SlugDriverMappingTest extends KernelTestCase
{
    public function testDriverMapsSlugConfiguration(): void
    {
        self::bootKernel();

        $driver = self::getContainer()->get(SlugDriver::class);
        $metadata = self::getContainer()->get('doctrine')->getManager()->getClassMetadata(Post::class);
        $metadata->initializeReflection(new RuntimeReflectionService());

        $extension = new ExtensionMetadata($metadata);
        $driver->loadMetadataForClass($extension);

        $config = $extension->getConfiguration(SlugConfiguration::class);

        self::assertNotNull($config);
        self::assertSame('name', $config->getMapping('slug')['source']);
    }
}
