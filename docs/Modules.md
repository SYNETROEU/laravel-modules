# Module Metadata

Every module has a `module.json` file:

```
Modules/
└── Billing/
    └── module.json
```

## Structure

```json
{
    "name": "Billing",
    "slug": "billing",
    "description": "Billing and invoicing functionality",
    "version": "1.0.0",
    "provider": "Modules\\Billing\\ModuleServiceProvider",
    "enabled": true,
    "dependencies": {
        "Payments": "^1.0",
        "Users": "^2.0"
    },
    "frontend": {
        "entry_points": ["Resources/js/Pages"],
        "layouts": ["Resources/js/Layouts"]
    },
    "routes": {
        "prefix": "billing",
        "middleware": ["web", "auth"]
    },
    "config": "Config/config.php",
    "migrations": "Database/migrations",
    "translations": "Resources/lang"
}
```

## Fields

| Field | Required | Description |
|-------|----------|-------------|
| `name` | Yes | Human-readable module name |
| `slug` | No | URL-friendly identifier (defaults to lowercase name) |
| `description` | No | Short description |
| `version` | No | Semantic version |
| `provider` | No | Module service provider class |
| `enabled` | No | Whether module is active (default: `true`) |
| `dependencies` | No | Required modules with version constraints |
| `frontend` | No | Frontend configuration |
| `routes` | No | Route configuration |
| `config` | No | Module config path |
| `migrations` | No | Migrations path |
| `translations` | No | Translation files path |

## Accessing Metadata

```php
use Synetro\LaravelModules\Facades\Modules;

$module = Modules::find('Billing');

$module->name();
$module->version();
$module->description();
$module->isEnabled();
$module->dependencies();
$module->path();
```

## Dynamic Metadata

Metadata can be extended by modules:

```php
// In ModuleServiceProvider
public function boot(): void
{
    $this->app->make(ModuleManager::class)
        ->extend('Billing', function ($module) {
            $module->addMeta('custom_key', 'custom_value');
        });
}
```
