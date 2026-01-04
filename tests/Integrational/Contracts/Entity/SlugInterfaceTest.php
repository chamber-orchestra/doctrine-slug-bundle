<?php

declare(strict_types=1);

namespace Tests\Integrational\Contracts\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Contracts\Entity\SlugInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SlugInterfaceTest extends KernelTestCase
{
    public function testInterfaceIsAutoloadable(): void
    {
        self::bootKernel();

        $entity = new SlugInterfaceEntity('Hello', 'hello');

        self::assertInstanceOf(SlugInterface::class, $entity);
        self::assertSame('Hello', $entity->getName());
        self::assertSame('hello', $entity->getSlug());
    }
}

final class SlugInterfaceEntity implements SlugInterface
{
    public function __construct(private string $name, private string $slug)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
