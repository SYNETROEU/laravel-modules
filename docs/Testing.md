# Testing

## Running Module Tests

```bash
# Run all module tests
php artisan module:test

# Run tests for a specific module
php artisan module:test Billing
```

## Test Structure

```
Modules/
└── Billing/
    └── Tests/
        ├── Feature/
        │   └── InvoiceTest.php
        └── Unit/
            └── InvoiceCalculatorTest.php
```

## Example Test

```php
<?php

declare(strict_types=1);

namespace Modules\Billing\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_invoice(): void
    {
        $response = $this->post('/billing/invoices', [
            'amount' => 100,
            'currency' => 'USD',
        ]);

        $response->assertStatus(201);
    }
}
```

## Isolated Testing

Each module's tests are isolated to its own directory.

The `module:test` command auto-discovers tests from:

```
Modules/*/Tests/
```

## Fuse Integration Tests

If Synetro Fuse is installed, use Fuse testing utilities:

```php
use Synetro\Fuse\Testing\FuseTestCase;

class InvoiceTest extends FuseTestCase
{
    //
}
```

## PHPUnit Configuration

The package respects your application's `phpunit.xml`.

Module tests run with the same configuration.

## Testing Without Fuse

The package works perfectly without Fuse installed.

```bash
composer require synetro/laravel-modules
# No Fuse required
```

## Testing Enabled/Disabled State

```php
use Synetro\LaravelModules\Facades\Modules;

public function test_disabled_module_routes_not_registered(): void
{
    Modules::disable('Billing');

    $this->assertFalse(Modules::isEnabled('Billing'));
}
```
