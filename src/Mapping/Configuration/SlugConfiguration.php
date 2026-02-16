<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Mapping\Configuration;

use ChamberOrchestra\MetadataBundle\Mapping\ORM\AbstractMetadataConfiguration;

/**
 * @phpstan-type SlugMapping array{slug: true, source: string, update: bool, length: int|null, nullable: bool, separator: string, fieldName: string}
 */
class SlugConfiguration extends AbstractMetadataConfiguration
{
    /**
     * @return array<string, SlugMapping>
     */
    public function getSluggableFields(): array
    {
        /** @var array<string, SlugMapping> $result */
        $result = \array_filter(
            $this->mappings,
            static fn (mixed $item): bool => \is_array($item) && !empty($item['slug']),
        );

        return $result;
    }
}
