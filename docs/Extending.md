# Extending

The package provides extension points without modifying core code.

## Extension Manager

```php
use Synetro\LaravelModules\Support\ModuleExtensionManager;

$extensions = app(ModuleExtensionManager::class);

$extensions->extend('billing', function ($module) {
    // Custom module logic
});
```

## Custom Generators

Register custom stub paths:

```php
$extensions->registerGeneratorStub('controller', '/path/to/custom/controller.stub');
```

## Custom Discovery

Register custom discovery classes:

```php
$extensions->registerDiscovery('actions', \App\Discovery\ActionDiscovery::class);
```

## Module Hooks

Subscribe to module lifecycle events:

```php
use Synetro\LaravelModules\Events\ModuleEnabled;

Event::listen(ModuleEnabled::class, function ($event) {
    $module = $event->module;
    // Do something when module is enabled
});
```

## Available Events

- `ModuleInstalling`
- `ModuleInstalled`
- `ModuleEnabling`
- `ModuleEnabled`
- `ModuleDisabling`
- `ModuleDisabled`
- `ModuleRemoving`
- `ModuleRemoved`

## Service Provider Hooks

In your module's `ModuleServiceProvider`:

```php
class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings
    }

    public function boot(): void
    {
        // Register routes, views, etc.
    }
}
```
