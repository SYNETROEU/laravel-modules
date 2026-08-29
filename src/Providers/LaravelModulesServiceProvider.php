<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Modules\ModuleManager;
use Synetro\LaravelModules\Frontend\FrontendManager;
use Synetro\LaravelModules\Inertia\InertiaPageResolver;
use Synetro\LaravelModules\Routing\ModuleRouteRegistrar;
use Synetro\LaravelModules\Support\ModuleCache;

class LaravelModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/modules.php', 'modules');

        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager($app->make(Filesystem::class), $app->make(ModuleCache::class));
        });

        $this->app->singleton(FrontendManager::class, function ($app) {
            return new FrontendManager($app->make(Filesystem::class), $app->make(ModuleCache::class));
        });

        $this->app->singleton(InertiaPageResolver::class, function ($app) {
            return new InertiaPageResolver($app->make(Filesystem::class), $app->make(FrontendManager::class));
        });

        $this->app->singleton(ModuleRouteRegistrar::class, function ($app) {
            return new ModuleRouteRegistrar($app->make(ModuleManagerInterface::class), $app->make(Filesystem::class));
        });

        $this->app->singleton(ModuleCache::class, function ($app) {
            return new ModuleCache($app->make(Filesystem::class));
        });

        $this->app->singleton(ModuleManagerInterface::class, ModuleManager::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }

        $this->loadModules();

        $this->registerRoutes();

        $this->registerInertiaIntegration();

        $this->registerViteIntegration();
    }

    protected function registerCommands(): void
    {
        $commands = [
        ];

        if (class_exists(\Synetro\LaravelModules\Console\ModuleListCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleListCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleMakeCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleMakeCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleEnableCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleEnableCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleDisableCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleDisableCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleRemoveCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleRemoveCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleMigrateCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleMigrateCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleTestCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleTestCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModulePublishCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModulePublishCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleBuildCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleBuildCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleDoctorCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleDoctorCommand::class;
        }
        if (class_exists(\Synetro\LaravelModules\Console\ModuleAboutCommand::class)) {
            $commands[] = \Synetro\LaravelModules\Console\ModuleAboutCommand::class;
        }

        $this->commands($commands);
    }

    protected function loadModules(): void
    {
        $manager = $this->app->make(ModuleManager::class);
        $manager->discover();
    }

    protected function registerRoutes(): void
    {
        $registrar = $this->app->make(ModuleRouteRegistrar::class);
        $registrar->register();
    }

    protected function registerInertiaIntegration(): void
    {
        if (! config('modules.inertia.enabled', true)) {
            return;
        }

        if (! class_exists(\Inertia\Inertia::class)) {
            return;
        }

        $resolver = $this->app->make(InertiaPageResolver::class);
        $resolver->register();
    }

    protected function registerViteIntegration(): void
    {
        if (! config('modules.vite.enabled', true)) {
            return;
        }

        $frontend = $this->app->make(FrontendManager::class);
        $frontend->register();
    }

    public function provides(): array
    {
        return [
            ModuleManager::class,
            FrontendManager::class,
            InertiaPageResolver::class,
            ModuleRouteRegistrar::class,
            ModuleCache::class,
        ];
    }
}
