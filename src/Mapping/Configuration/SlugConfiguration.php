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

class SlugConfiguration extends AbstractMetadataConfiguration
{
    public function getSluggableFields(): array
    {
        return \array_filter($this->mappings, function (array $item): bool {
            return isset($item['slug']) && $item['slug'];
        });
    }
}