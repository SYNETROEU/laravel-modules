<?php

declare(strict_types=1);

if (! function_exists('module_path')) {
    function module_path(string $module, string $path = ''): string
    {
        $manager = app(\Synetro\LaravelModules\Contracts\ModuleManagerInterface::class);
        $base = $manager->path($module);

        if ($base === null) {
            throw new \InvalidArgumentException("Module [{$module}] not found.");
        }

        return $path ? $base.'/'.$path : $base;
    }
}

if (! function_exists('module_config')) {
    function module_config(string $module, string $key, mixed $default = null): mixed
    {
        $manager = app(\Synetro\LaravelModules\Contracts\ModuleManagerInterface::class);

        return $manager->config($module, $default);
    }
}

if (! function_exists('module_routes')) {
    function module_routes(string $module): ?string
    {
        $manager = app(\Synetro\LaravelModules\Contracts\ModuleManagerInterface::class);

        return $manager->routes($module);
    }
}
