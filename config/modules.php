<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Directory
    |--------------------------------------------------------------------------
    |
    | This value defines the root directory for all modules. The package will
    | scan this directory for module.json files.
    |
    */

    'directory' => base_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Modules Namespace
    |--------------------------------------------------------------------------
    |
    | This value defines the root PHP namespace for all modules.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Enable manifest caching for production. The manifest contains the list
    | of discovered modules, their metadata, and frontend pages.
    |
    */

    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | Manifest Path
    |--------------------------------------------------------------------------
    |
    | The path where the module manifest cache is stored.
    |
    */

    'manifest' => storage_path('framework/cache/modules.php'),

    /*
    |--------------------------------------------------------------------------
    | Default Module Structure
    |--------------------------------------------------------------------------
    |
    | Define which directories are created when generating a new module.
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
    |
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
    |
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
    |
    | Enable automatic integration with Synetro Fuse when available.
    |
    */

    'fuse_integration' => true,

];
