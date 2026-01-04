[![PHP Composer](https://github.com/chamber-orchestra/doctrine-slug-bundle/actions/workflows/php.yml/badge.svg)](https://github.com/chamber-orchestra/doctrine-slug-bundle/actions/workflows/php.yml)

# Doctrine Slug Bundle

Symfony bundle that generates unique, URL-friendly slugs for Doctrine ORM entities using PHP 8 attributes. It integrates with Chamber Orchestra metadata and Doctrine listeners to keep slugs consistent on persist and (optionally) on update.

## Installation

```bash
composer require chamber-orchestra/doctrine-slug-bundle
```

If Symfony Flex does not auto-register the bundle, add it manually:

```php
// config/bundles.php
return [
    ChamberOrchestra\DoctrineSlugBundle\ChamberOrchestraDoctrineSlugBundle::class => ['all' => true],
];
```

## Dependencies

Runtime requirements are managed by Composer. The bundle depends on:

- PHP 8.4+
- `chamber-orchestra/metadata-bundle`
- `symfony/string`
- `symfony/translation-contracts`
- Doctrine ORM + DoctrineBundle (for entity listeners)

## Usage

Annotate a sluggable property with the `#[Slug]` attribute and ensure the slug column is unique.

```php
namespace App\Entity;

use ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute\Slug;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Slug(source: 'name')]
    private string $slug = '';

    public function getSlug(): string
    {
        return $this->slug;
    }
}
```

Options:

- `source`: source field name for slug generation.
- `update`: set to `true` to regenerate slug when the source changes.
- `separator`: character used between words (default `-`).

Notes:

- The slug column must be `unique`.
- If the source column is nullable and the slug column is not, mapping will throw.

### Using the SlugTrait

You can also reuse the provided trait:

```php
use ChamberOrchestra\DoctrineSlugBundle\Entity\SlugTrait;

class Post
{
    use SlugTrait;
}
```

The trait defines `name` and `slug` fields with proper Doctrine mapping and a `#[Slug(source: 'name')]` attribute.

## Running Tests

```bash
composer test
```

This runs PHPUnit with the configuration in `phpunit.xml.dist`.
