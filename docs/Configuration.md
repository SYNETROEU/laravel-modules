# Configuration

## config/modules.php

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Modules Directory
    |--------------------------------------------------------------------------
    |
    | Root directory for all modules.
    |
    */

    'directory' => base_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Modules Namespace
    |--------------------------------------------------------------------------
    |
    | Root PHP namespace for all modules.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Enable manifest caching for production.
    |
    */

    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | Manifest Path
    |--------------------------------------------------------------------------
    |
    | Where the module manifest cache is stored.
    |
    */

    'manifest' => storage_path('framework/cache/modules.php'),

    /*
    |--------------------------------------------------------------------------
    | Default Module Structure
    |--------------------------------------------------------------------------
    |
    | Directories created when generating a new module.
    |
    */

    'structure' => [
        'Actions',
        'Console/Commands',
        'Contracts',
        'Events',
        'Exceptions',
        'Http/Controllers',
        'Http/Middleware',
        'Http/Requests',
        'Listeners',
        'Models',
        'Notifications',
        'Policies',
        'Providers',
        'Services',
        'Database/factories',
        'Database/migrations',
        'Resources/js/Pages',
        'Resources/js/Components',
        'Resources/js/Layouts',
        'Resources/js/hooks',
        'Resources/lang',
        'Resources/views',
        'Routes',
        'Config',
        'Tests/Feature',
        'Tests/Unit',
    ],

    /*
    |--------------------------------------------------------------------------
    | Inertia Configuration
    |--------------------------------------------------------------------------
    */

    'inertia' => [
        'enabled' => true,
        'namespace_separator' => '::',
        'default_layout' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Vite Configuration
    |--------------------------------------------------------------------------
    */

    'vite' => [
        'enabled' => true,
        'auto_discover' => true,
        'entry_points' => [
            'Resources/js/Pages/**/*.tsx',
            'Resources/js/Pages/**/*.jsx',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fuse Integration
    |--------------------------------------------------------------------------
    */

    'fuse_integration' => true,

];
```
