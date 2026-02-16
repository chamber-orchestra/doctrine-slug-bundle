<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Integrational\Fixtures\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Entity\SlugTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Post
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
