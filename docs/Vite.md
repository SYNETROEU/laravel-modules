# Vite Integration

## Automatic Discovery

The package automatically discovers module JavaScript entry points.

Development mode scans the filesystem for changes.

Production mode uses a cached manifest.

## Module Entry Points

Module pages are automatically added to the Vite build.

```
Modules/
└── Billing/
    └── Resources/
        └── js/
            └── Pages/
                └── Dashboard.tsx   ← auto-discovered
```

## Vite Plugin

For advanced configuration, use the included Vite plugin:

```ts
// vite.config.ts
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import modules from 'synetro-laravel-modules/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.tsx',
                ...modules().entryPoints,
            ],
            refresh: true,
        }),
        modules(),
    ],
})
```

## HMR

Hot Module Replacement works automatically for module pages.

## Production Build

```bash
php artisan module:build
npm run build
```

The manifest is rebuilt for production.

## Disable Vite Integration

```php
// config/modules.php
'vite' => [
    'enabled' => false,
],
```

## Frontend Manager API

```php
use Synetro\LaravelModules\Facades\Modules;

// Get all discovered pages
$pages = Modules::frontend()->pages('Billing');

// Resolve a specific page
$page = Modules::frontend()->resolve('Billing', 'Dashboard');
```
