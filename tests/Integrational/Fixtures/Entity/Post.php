<?php

declare(strict_types=1);

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
