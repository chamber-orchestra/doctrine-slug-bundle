<?php

declare(strict_types=1);

namespace Tests\Unit\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Entity\SlugTrait;
use PHPUnit\Framework\TestCase;

final class SlugTraitTest extends TestCase
{
    public function testItReturnsStoredValues(): void
    {
        $entity = new SlugTraitEntity();
        $entity->setName('Hello');
        $entity->setSlug('hello');

        self::assertSame('Hello', $entity->getName());
        self::assertSame('hello', $entity->getSlug());
    }
}

final class SlugTraitEntity
{
    use SlugTrait;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
