# Dependencies

Modules can depend on other modules.

```json
{
    "name": "Billing",
    "dependencies": {
        "Payments": "^1.0",
        "Users": "^2.0"
    }
}
```

## Validation

When enabling a module, dependencies are validated:

1. **Module exists** - The dependency must be installed
2. **Module enabled** - The dependency must be enabled

If validation fails, you see:

```
Cannot enable Billing.

Billing requires:
  Payments ^1.0

Payments is installed but disabled.
Run:
  php artisan module:enable Payments
```

## Circular Dependencies

Circular dependencies are detected:

```json
{
    "name": "A",
    "dependencies": { "B": "^1.0" }
}
```

```json
{
    "name": "B",
    "dependencies": { "A": "^1.0" }
}
```

Attempting to enable either module will fail with a clear error.

## Optional Dependencies

Use the `optional_dependencies` field for soft requirements:

```json
{
    "name": "Billing",
    "dependencies": {
        "Payments": "^1.0"
    },
    "optional_dependencies": {
        "Notifications": "^1.0"
    }
}
```

## Dependency Graph

View the dependency graph:

```bash
php artisan module:doctor
```

## Programmatic Access

```php
use Synetro\LaravelModules\Facades\Modules;

$billing = Modules::find('Billing');

$billing->dependencies();
// ['Payments' => '^1.0', 'Users' => '^2.0']

$dependents = Modules::manager()->findDependents('Payments');
// ['Billing']
```
