<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute as Dev;
use Doctrine\ORM\Mapping as ORM;

trait SlugTrait
{
    #[ORM\Column(type: 'string', length: 127, nullable: false)]
    protected string $name = '';
    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Dev\Slug(source: 'name')]
    protected string $slug = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
