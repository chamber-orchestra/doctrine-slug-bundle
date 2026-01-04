# Repository Guidelines

## Project Structure & Module Organization
- `src/` contains the bundle source code (PSR-4 namespace `ChamberOrchestra\DoctrineSlugBundle\`). Key areas include mapping/configuration, event subscribers, and slug generation.
- `src/Resources/config/` holds Symfony service definitions.
- `tests/` is reserved for PHPUnit tests (currently only `tests/Integrational/TestKernel.php`).
- `bin/phpunit` is the local test runner wrapper; `vendor/` is Composer-managed dependencies.

## Build, Test, and Development Commands
- `composer install` installs runtime and dev dependencies.
- `composer test` runs the PHPUnit suite via `vendor/bin/phpunit`.
- `vendor/bin/phpunit --filter Name` runs a focused test set; use when iterating on a specific feature.

## Coding Style & Naming Conventions
- PHP 8.4+, `declare(strict_types=1)` at the top of PHP files.
- Follow PSR-12 formatting: 4-space indents, braces on the next line, and one class per file.
- Class names are PascalCase, methods camelCase, constants UPPER_SNAKE_CASE.
- Keep namespaces aligned with directory structure under `src/` and `tests/` (PSR-4).

## Testing Guidelines
- PHPUnit is configured in `phpunit.xml.dist` with `tests/` as the suite root.
- Add tests under `tests/` and name them `*Test.php` (e.g., `tests/Slug/StringGeneratorTest.php`).
- Prefer integration tests that exercise the bundle wiring; update `Tests\Integrational\TestKernel` if services/configuration change.

## Commit & Pull Request Guidelines
- This repository has no commit history yet; use concise, imperative commit messages (e.g., "Add slug attribute mapping").
- Keep commits focused and include tests or fixtures when behavior changes.
- PRs should describe the change, list any new configuration, and include test commands or results. Add screenshots only if developer tooling/UI is affected.

## Configuration Tips
- Symfony service configuration lives in `src/Resources/config/services.php`; update it when adding new services or tags.
- Bundle wiring is in `src/ChamberOrchestraDoctrineSlugBundle.php` and `src/DependencyInjection/ChamberOrchestraDoctrineSlugExtension.php`.
