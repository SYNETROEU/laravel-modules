# Artisan Commands

## module:list

List all modules with status and dependencies.

```bash
php artisan module:list
```

## module:make

Create a new module.

```bash
php artisan module:make Blog
php artisan module:make Blog --full
php artisan module:make Blog --inertia
php artisan module:make Blog --api
php artisan module:make Blog --full --inertia --api
```

Options:
- `--full` - Generate complete module structure
- `--inertia` - Include Inertia.js page scaffolding
- `--api` - Generate API routes and controller
- `--force` - Overwrite existing module

## module:enable

Enable a disabled module.

```bash
php artisan module:enable Blog
```

## module:disable

Disable an enabled module.

```bash
php artisan module:disable Blog
```

Disabled modules do not register routes, services, or migrations.

## module:remove

Remove a module.

```bash
php artisan module:remove Blog
php artisan module:remove Blog --force
php artisan module:remove Blog --keep-data
```

Options:
- `--force` - Skip confirmation
- `--keep-data` - Keep database tables and data

## module:migrate

Run migrations for a specific module or all modules.

```bash
php artisan module:migrate
php artisan module:migrate Blog
```

## module:test

Run tests for a specific module or all modules.

```bash
php artisan module:test
php artisan module:test Blog
```

## module:publish

Publish module assets.

```bash
php artisan module:publish Blog
php artisan module:publish Blog --config
php artisan module:publish Blog --lang
php artisan module:publish Blog --views
php artisan module:publish Blog --assets
php artisan module:publish Blog --all
```

## module:build

Build module manifests and caches.

```bash
php artisan module:build
```

## module:doctor

Diagnose module health.

```bash
php artisan module:doctor
```

## module:about

Display package information.

```bash
php artisan module:about
```
