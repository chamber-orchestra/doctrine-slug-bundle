[![PHP Composer](https://github.com/chamber-orchestra/doctrine-slug-bundle/actions/workflows/php.yml/badge.svg)](https://github.com/chamber-orchestra/doctrine-slug-bundle/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/chamber-orchestra/doctrine-slug-bundle/graph/badge.svg)](https://codecov.io/gh/chamber-orchestra/doctrine-slug-bundle)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen)](https://phpstan.org/)
[![Latest Stable Version](https://poser.pugx.org/chamber-orchestra/doctrine-slug-bundle/v)](https://packagist.org/packages/chamber-orchestra/doctrine-slug-bundle)
[![License](https://poser.pugx.org/chamber-orchestra/doctrine-slug-bundle/license)](https://packagist.org/packages/chamber-orchestra/doctrine-slug-bundle)
![Symfony 8](https://img.shields.io/badge/Symfony-8-purple?logo=symfony)
![PHP 8.5](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![Doctrine ORM 3](https://img.shields.io/badge/Doctrine%20ORM-3-orange?logo=doctrine&logoColor=white)

# Doctrine Slug Bundle

A Symfony bundle that automatically generates unique, URL-friendly slugs for Doctrine ORM entities using native PHP 8 attributes. Slugs are created on persist and optionally regenerated on update, with built-in collision resolution (`hello-world`, `hello-world-1`, `hello-world-2`, ...).

## Features

- Declarative configuration via `#[Slug]` PHP attribute
- Automatic unique slug generation with collision suffixes
- Optional slug regeneration on entity update
- Configurable separator character and column length
- Reusable `SlugTrait` for common name/slug entity patterns
- Mapping validation (unique constraint, nullable consistency, source type checking)
- Integration with `chamber-orchestra/metadata-bundle` and Doctrine event listeners

## Requirements

- PHP 8.5+
- Symfony 8.0+
- Doctrine ORM 3.6+ / DoctrineBundle 3.2+
- `chamber-orchestra/metadata-bundle` 8.0.*

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

## Usage

Annotate a property with the `#[Slug]` attribute. The slug column **must** be `unique`.

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

    // getters ...
}
```

### Attribute Options

| Option | Type | Default | Description |
|-----------|--------|---------|----------------------------------------------|
| `source` | string | — | Source property name for slug generation |
| `update` | bool | `false` | Regenerate slug when the source field changes |
| `separator`| string | `-` | Word separator character |

### Using the SlugTrait

For entities with a standard `name`/`slug` pattern:

```php
use ChamberOrchestra\DoctrineSlugBundle\Contracts\Entity\SlugInterface;
use ChamberOrchestra\DoctrineSlugBundle\Entity\SlugTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Post implements SlugInterface
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
}
```

The trait provides `$name` (varchar 127), `$slug` (varchar 255, unique) with `getName()`, `setName()`, and `getSlug()` accessors.

### Mapping Constraints

The bundle validates mappings at metadata load time:

- Slug column must have `unique: true`
- Source property must exist and be a string type (`string`, `text`, or `ascii_string`)
- If source is nullable, slug must also be nullable
- Separator must be exactly one character

## Development

```bash
composer test       # Run PHPUnit test suite
composer analyse    # Run PHPStan static analysis (level max)
composer cs-fix     # Fix code style with PHP-CS-Fixer
composer cs-check   # Verify code style (dry-run)
```

## License

[MIT](LICENSE)
