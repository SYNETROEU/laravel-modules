# Laravel Modules

[![PHP Version](https://img.shields.io/badge/php-%208.3%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-12%2B-red)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

**Modern Laravel module system with first-class Inertia.js + React support.**

No manual `app.tsx` edits. No manually maintained page registries. No boilerplate.

```bash
composer require synetro/laravel-modules
php artisan module:make Billing --full --inertia
```

Then immediately:

```php
return Inertia::render('Billing::Dashboard');
```

That's it.

---

## Why?

Existing Laravel module packages force you into Blade-centric workflows, require manual frontend registries, and create more problems than they solve.

This package was built from scratch to work natively with:

- Laravel 12+
- Inertia.js
- React
- Vite
- TypeScript

Modules should feel like a first-class part of your application — not an afterthought.

---

## Installation

```bash
composer require synetro/laravel-modules
```

Publish the config (optional):

```bash
php artisan vendor:publish --tag=modules-config
```

---

## Quick Start

```bash
php artisan module:make Blog --full --inertia
php artisan module:enable Blog
```

Visit `/blog` to see your new module.

---

## Features

- Zero-config module discovery
- Automatic Inertia page resolution
- Automatic Vite integration
- Module lifecycle (enable/disable/remove)
- Dependency management with validation
- Artisan generators for controllers, models, pages, components, and more
- Optional Fuse integration
- Isolated module testing
- Route registration per module
- Module-specific config, translations, and views
- Doctor command for diagnostics
- Production-ready manifest caching

---

## Documentation

See the `docs/` directory for full documentation.

## License

MIT
