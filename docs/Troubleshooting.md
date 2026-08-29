# Troubleshooting

## Module Not Found

If a module is not discovered:

1. Check `module.json` exists in the module root
2. Check the `Modules` directory path in `config/modules.php`
3. Run `php artisan module:build`
4. Clear cache: `php artisan module:build`

## Routes Not Loading

1. Ensure the module is enabled: `php artisan module:list`
2. Check `Routes/web.php` exists in the module
3. Run `php artisan route:clear`

## Inertia Page Not Found

1. Check the page file exists in `Resources/js/Pages/`
2. Verify the namespace: `Billing::Dashboard`
3. Run `php artisan module:build`

## Permission Errors

Ensure the web server has write access to:

- `storage/framework/cache/`
- `Modules/` directory

## Dependency Errors

```bash
php artisan module:doctor
```

Shows broken dependencies.

## Fuse Conflicts

Disable Fuse integration if needed:

```php
// config/modules.php
'fuse_integration' => false,
```

## Cache Issues

Clear the module cache:

```bash
php artisan module:build
```

Or delete the manifest file:

```bash
rm storage/framework/cache/modules.php
```
