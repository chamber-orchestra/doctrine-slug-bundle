<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Mapping\Driver;

use ChamberOrchestra\DoctrineSlugBundle\Exception\MappingException;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration\SlugConfiguration;
use ChamberOrchestra\MetadataBundle\Mapping\Driver\AbstractMappingDriver;
use ChamberOrchestra\MetadataBundle\Mapping\ExtensionMetadataInterface;
use ChamberOrchestra\MetadataBundle\Mapping\ORM\ExtensionMetadata;
use Doctrine\ORM\Mapping\Column;

class SlugDriver extends AbstractMappingDriver
{
    public function loadMetadataForClass(ExtensionMetadataInterface $extensionMetadata): void
    {
        $className = $extensionMetadata->getName();
        /** @var ExtensionMetadata $extensionMetadata */
        $class = $extensionMetadata->getOriginMetadata()->getReflectionClass();

        $config = new SlugConfiguration();
        foreach ($class->getProperties() as $property) {
            /** @var Slug|null $attr */
            $attr = $this->reader->getPropertyAttribute($property, Slug::class);
            if (null === $attr) {
                continue;
            }

            if (!$class->hasProperty($attr->source)) {
                throw MappingException::missingProperty($className, $attr->source, $property->getName());
            }

            /** @var Column|null $column */
            $column = $this->reader->getPropertyAttribute($property, Column::class);
            if (null === $column) {
                throw MappingException::missingAttribute($className, $property->getName(), Column::class);
            }

            if (!$column->unique) {
                throw MappingException::notUnique($className, $property->getName());
            }

            $sourceProperty = $class->getProperty($attr->source);
            /** @var Column|null $sourceColumn */
            $sourceColumn = $this->reader->getPropertyAttribute($sourceProperty, Column::class);

            if (null !== $sourceColumn) {
                if (!\in_array($sourceColumn->type, ['string', 'text', 'ascii_string'], true)) {
                    throw MappingException::invalidSourceType($className, $attr->source, $property->getName(), (string) $sourceColumn->type);
                }

                if ($sourceColumn->nullable && !$column->nullable) {
                    throw MappingException::notNullableWithSourceNullable($className, $attr->source, $property->getName());
                }
            }

            $config->mapField($property->getName(), [
                'slug' => true,
                'source' => $attr->source,
                'update' => $attr->update,
                'length' => $column->length,
                'nullable' => $column->nullable,
                'separator' => $attr->separator,
            ]);
        }

        $extensionMetadata->addConfiguration($config);
    }

    protected function getPropertyAttribute(): string|null
    {
        return Slug::class;
    }
}
