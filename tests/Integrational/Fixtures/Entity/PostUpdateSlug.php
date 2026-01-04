<?php

declare(strict_types=1);

namespace Tests\Integrational\Fixtures\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PostUpdateSlug
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name = '';

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[Slug(source: 'name', update: true)]
    private string $slug = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
