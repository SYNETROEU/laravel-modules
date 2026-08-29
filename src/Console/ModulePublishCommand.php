<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModulePublishCommand extends Command
{
    protected $signature = 'module:publish
                            {name? : The module name}
                            {--config : Publish config}
                            {--lang : Publish translations}
                            {--views : Publish views}
                            {--assets : Publish assets}
                            {--all : Publish all}';

    protected $description = 'Publish module assets';

    public function handle(ModuleManagerInterface $modules): int
    {
        $name = $this->argument('name');

        if (! $name) {
            $this->error('Please specify a module name.');

            return Command::FAILURE;
        }

        $module = $modules->find($name);

        if ($module === null) {
            $this->error("Module [{$name}] not found.");

            return Command::FAILURE;
        }

        $this->info("Publishing assets for [{$name}]...");

        if ($this->option('all')) {
            $this->publishAll($module);
        } else {
            if ($this->option('config')) {
                $this->publishConfig($module);
            }
            if ($this->option('lang')) {
                $this->publishLang($module);
            }
            if ($this->option('views')) {
                $this->publishViews($module);
            }
            if ($this->option('assets')) {
                $this->publishAssets($module);
            }
        }

        $this->info("Publishing complete.");

        return Command::SUCCESS;
    }

    protected function publishAll(\Synetro\LaravelModules\Modules\Module $module): void
    {
        $this->publishConfig($module);
        $this->publishLang($module);
        $this->publishViews($module);
        $this->publishAssets($module);
    }

    protected function publishConfig(\Synetro\LaravelModules\Modules\Module $module): void
    {
        $configPath = $module->configPath();

        if ($configPath === null) {
            $this->warn("  No config found for [{$module->name()}].");

            return;
        }

        $this->info("  Publishing config...");
    }

    protected function publishLang(\Synetro\LaravelModules\Modules\Module $module): void
    {
        $langPath = $module->langPath();

        if ($langPath === null) {
            $this->warn("  No translations found for [{$module->name()}].");

            return;
        }

        $this->info("  Publishing translations...");
    }

    protected function publishViews(\Synetro\LaravelModules\Modules\Module $module): void
    {
        $viewsPath = $module->viewsPath();

        if ($viewsPath === null) {
            $this->warn("  No views found for [{$module->name()}].");

            return;
        }

        $this->info("  Publishing views...");
    }

    protected function publishAssets(\Synetro\LaravelModules\Modules\Module $module): void
    {
        $this->info("  Publishing assets...");
    }
}
