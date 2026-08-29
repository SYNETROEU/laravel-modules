<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Routing;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleRouteRegistrar
{
    private array $registered = [];

    public function __construct(
        protected ModuleManagerInterface $modules,
        protected Filesystem $files,
    ) {}

    public function register(): void
    {
        foreach ($this->modules->enabled() as $name => $module) {
            $this->registerModuleRoutes($module);
        }
    }

    protected function registerModuleRoutes(\Synetro\LaravelModules\Modules\Module $module): void
    {
        if (isset($this->registered[$module->name()])) {
            return;
        }

        $routesPath = $module->path().'/Routes';

        if (! $this->files->isDirectory($routesPath)) {
            return;
        }

        $webRoutes = $routesPath.'/web.php';
        $apiRoutes = $routesPath.'/api.php';

        if ($this->files->exists($webRoutes)) {
            Route::middleware('web')
                ->prefix($this->resolvePrefix($module))
                ->name($this->resolveName($module))
                ->group($webRoutes);
        }

        if ($this->files->exists($apiRoutes)) {
            Route::middleware('api')
                ->prefix('api/'.$this->resolvePrefix($module))
                ->name('api.'.$this->resolveName($module))
                ->group($apiRoutes);
        }

        $this->registered[$module->name()] = true;
    }

    protected function resolvePrefix(\Synetro\LaravelModules\Modules\Module $module): string
    {
        return $module->slug();
    }

    protected function resolveName(\Synetro\LaravelModules\Modules\Module $module): string
    {
        return $module->slug().'.';
    }
}
