<?php

declare(strict_types=1);

namespace Tests\Integrational\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Integrational\Fixtures\Entity\Post;
use Tests\Integrational\Fixtures\Entity\PostShortSlug;
use Tests\Integrational\Fixtures\Entity\PostUpdateSlug;

final class SlugSubscriberWorkflowTest extends KernelTestCase
{
    private ?EntityManagerInterface $em = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = self::getContainer()->get('doctrine')->getManager();

        $tool = new SchemaTool($this->em);
        $classes = [
            $this->em->getClassMetadata(Post::class),
            $this->em->getClassMetadata(PostShortSlug::class),
            $this->em->getClassMetadata(PostUpdateSlug::class),
        ];
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }

    protected function tearDown(): void
    {
        if ($this->em) {
            $this->em->clear();
        }

        $this->em = null;
        parent::tearDown();
    }

    public function testSlugIsGeneratedOnPersist(): void
    {
        $post = new Post();
        $post->setName('Hello World');

        $this->em->persist($post);
        $this->em->flush();

        self::assertSame('hello-world', $post->getSlug());
    }

    public function testSlugIsUniqueAcrossEntities(): void
    {
        $first = new Post();
        $first->setName('Hello World');

        $second = new Post();
        $second->setName('Hello World');

        $this->em->persist($first);
        $this->em->persist($second);
        $this->em->flush();

        self::assertSame('hello-world', $first->getSlug());
        self::assertSame('hello-world-1', $second->getSlug());
    }

    public function testSlugIsNotUpdatedWhenSourceChanges(): void
    {
        $post = new Post();
        $post->setName('Hello World');

        $this->em->persist($post);
        $this->em->flush();

        $post->setName('Updated Title');
        $this->em->flush();

        self::assertSame('hello-world', $post->getSlug());
    }

    public function testSlugUpdatesWhenUpdateTrue(): void
    {
        $post = new PostUpdateSlug();
        $post->setName('Hello World');

        $this->em->persist($post);
        $this->em->flush();

        $post->setName('Updated Title');
        $this->em->flush();

        self::assertSame('updated-title', $post->getSlug());
    }

    public function testSlugUniquenessTrimsToLength(): void
    {
        $first = new PostShortSlug();
        $first->setName('Hello World');

        $second = new PostShortSlug();
        $second->setName('Hello World');

        $this->em->persist($first);
        $this->em->persist($second);
        $this->em->flush();

        self::assertSame('hello-worl', $first->getSlug());
        self::assertSame('hello-wo-1', $second->getSlug());
    }
}
