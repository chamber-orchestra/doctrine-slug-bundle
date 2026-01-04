<?php

declare(strict_types=1);

namespace Tests\Integrational\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Entity\SlugTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugTraitTest extends KernelTestCase
{
    public function testTraitWorksInKernelContext(): void
    {
        self::bootKernel();

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
