# Routing

## Module Routes

Each module can define its own routes:

```
Modules/Billing/Routes/web.php
Modules/Billing/Routes/api.php
```

## Web Routes

```php
// Modules/Billing/Routes/web.php
use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');
```

Automatically prefixed with `/billing` and named `billing.dashboard`.

## API Routes

```php
// Modules/Billing/Routes/api.php
use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\Api\InvoiceController;

Route::get('/invoices', [InvoiceController::class, 'index']);
```

Automatically prefixed with `/api/billing`.

## Disabled Modules

Disabled modules do not register any routes.

## Route Collision Detection

The `module:doctor` command detects route collisions across modules.

## Named Routes

Access module routes by name:

```php
route('billing.dashboard')
route('billing.invoices.index')
```

## Middleware

Apply middleware in your module routes:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

## Custom Prefix

Override the default prefix in `module.json`:

```json
{
    "name": "Billing",
    "slug": "billing",
    "prefix": "admin/billing"
}
```
