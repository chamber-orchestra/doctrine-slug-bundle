# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Symfony bundle (`chamber-orchestra/doctrine-slug-bundle`) that generates unique, URL-friendly slugs for Doctrine ORM entities using PHP 8.4 attributes. Integrates with `chamber-orchestra/metadata-bundle` and Doctrine event listeners.

## Commands

```bash
composer install          # Install dependencies
composer test             # Run full PHPUnit suite
vendor/bin/phpunit --filter ClassName  # Run specific test(s)
```

## Architecture

The slug lifecycle flows through these layers:

1. **Mapping** (`src/Mapping/`) — `SlugDriver` reads `#[Slug]` attributes from entity properties, validates constraints (source exists, column is unique, nullable consistency), and produces `SlugConfiguration` metadata.
2. **Generation** (`src/Slug/Generator/`) — `StringGenerator` implements `GeneratorInterface`, delegates to Symfony's `SluggerInterface` for lowercasing/formatting, and handles length truncation.
3. **Persistence** (`src/EventSubscriber/SlugSubscriber.php`) — Doctrine listener on `onFlush`/`postFlush` that generates slugs on insert (and optionally on update). Resolves uniqueness collisions by appending `-1`, `-2`, etc.

Supporting pieces:
- `SlugTrait` (`src/Entity/`) — reusable trait providing `$name`, `$slug`, and accessors with pre-configured `#[Slug(source: 'name')]`.
- `SlugInterface` (`src/Contracts/Entity/`) — contract requiring `getName()` and `getSlug()`.
- Bundle wiring in `ChamberOrchestraDoctrineSlugBundle` + `ChamberOrchestraDoctrineSlugExtension`; services defined in `src/Resources/config/services.php`.

## Testing

- Tests live in `tests/` — both `Unit/` and `Integrational/` directories mirror `src/` structure.
- Integration tests use `KernelTestCase` with `tests/Integrational/TestKernel.php` (boots FrameworkBundle, MetadataBundle, DoctrineBundle, and this bundle with SQLite in-memory).
- Fixture entities in `tests/Integrational/Fixtures/Entity/` (`Post`, `PostUpdateSlug`, `PostShortSlug`).
- Update `TestKernel` when adding new services or changing bundle configuration.

## Coding Conventions

- PHP 8.4+, `declare(strict_types=1)` on every file.
- PSR-12 formatting: 4-space indents, braces on next line, one class per file.
- PSR-4 autoloading: `ChamberOrchestra\DoctrineSlugBundle\` → `src/`, `Tests\` → `tests/`.
- Apache 2.0 copyright header block on all source files.
