# Fuse Integration

The package integrates with [Synetro Fuse](https://github.com/SYNETROEU/synetro-fuse) when available.

Fuse is **optional**. Everything works without it.

## Auto-Detection

Fuse is detected automatically:

```php
// config/modules.php
'fuse_integration' => true,
```

## What Integrates

When Fuse is installed, generated modules use:

- Fuse Actions instead of plain action classes
- Fuse Resources for API endpoints
- Fuse validation
- Fuse API responses
- Fuse testing utilities
- Fuse OpenAPI documentation

## Generated Actions

```php
// With Fuse
use Synetro\Fuse\Actions\Action;

class CreateInvoice extends Action
{
    public function handle(mixed $payload): mixed
    {
        return Invoice::create($payload);
    }
}
```

## Generated Resources

```php
// With Fuse
use Synetro\Fuse\Support\Facades\Fuse;

Fuse::resource(Invoice::class)
    ->search(['number', 'client_name'])
    ->filter(['status', 'currency'])
    ->paginate(25)
    ->register();
```

## Without Fuse

Plain Laravel implementations are generated:

```php
class CreateInvoice
{
    public function handle(array $payload): Invoice
    {
        return Invoice::create($payload);
    }
}
```

## Fuse Health Checks

Module health is integrated with Fuse health system when available:

```bash
php artisan fuse:health
php artisan module:doctor
```

## Fuse Discovery

Module discovery uses Fuse discovery when available for performance.

## Disable Integration

```php
// config/modules.php
'fuse_integration' => false,
```
