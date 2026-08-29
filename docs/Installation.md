# Installation

## Requirements

- PHP 8.3+
- Laravel 12.0+ or 13.0+
- Composer

## Install

```bash
composer require synetro/laravel-modules
```

The package will auto-discover via Laravel package discovery.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=modules-config
```

This creates `config/modules.php`.

## Verify

```bash
php artisan module:about
```

## Directory Setup

Ensure your `Modules` directory exists at the project root:

```bash
mkdir Modules
```

Or configure a custom path in `config/modules.php`:

```php
'directory' => base_path('app/Modules'),
```

## Inertia Setup

If using Inertia.js, the package automatically resolves module pages.

No changes to `resources/js/app.tsx` are needed.

## Vite Setup

The package integrates with Vite automatically.

Module pages are discovered from:

```
Modules/*/Resources/js/Pages/
```

No manual `resolvePageComponent` entries needed.

## Fuse Integration

If [Synetro Fuse](https://github.com/SYNETROEU/synetro-fuse) is installed, the package will automatically integrate with it.

No configuration needed.

To disable Fuse integration:

```php
// config/modules.php
'fuse_integration' => false,
```
