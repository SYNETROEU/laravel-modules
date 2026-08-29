# Quick Start

## Create Your First Module

```bash
php artisan module:make Blog --full --inertia
```

This creates:

```
Modules/
└── Blog/
    ├── module.json
    ├── ModuleServiceProvider.php
    ├── Http/Controllers/BlogController.php
    ├── Models/
    ├── Policies/
    ├── Database/migrations/
    ├── Resources/js/Pages/
    │   ├── Index.tsx
    │   └── Create.tsx
    ├── Resources/js/Components/
    │   └── ExampleCard.tsx
    ├── Routes/
    │   ├── web.php
    │   └── api.php
    └── Tests/
```

## Enable the Module

```bash
php artisan module:enable Blog
```

## Use Inertia

```php
// Modules/Blog/Http/Controllers/BlogController.php
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog::Index');
    }
}
```

The page at `Modules/Blog/Resources/js/Pages/Index.tsx` is automatically resolved.

No `app.tsx` editing. No manual page registry.

## API-Only Module

```bash
php artisan module:make Api --api
```

Creates a module with `Routes/api.php` and an API controller.

## Full Featured Module

```bash
php artisan module:make Billing --full --inertia --api
```

Everything included.

## List Modules

```bash
php artisan module:list
```

## Disable a Module

```bash
php artisan module:disable Blog
```

## Remove a Module

```bash
php artisan module:remove Blog
```
