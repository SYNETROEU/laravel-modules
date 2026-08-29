<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleMigrateCommand extends Command
{
    protected $signature = 'module:migrate {name? : The module name}';

    protected $description = 'Run module migrations';

    public function handle(ModuleManagerInterface $modules): int
    {
        $name = $this->argument('name');

        if ($name) {
            $module = $modules->find($name);

            if ($module === null) {
                $this->error("Module [{$name}] not found.");

                return Command::FAILURE;
            }

            if (! $module->migrationsPath()) {
                $this->warn("Module [{$name}] has no migrations.");

                return Command::SUCCESS;
            }

            $this->info("Running migrations for [{$name}]...");
            Artisan::call('migrate', ['--path' => $module->migrationsPath()]);
            $this->line(Artisan::output());
        } else {
            $this->info("Running all module migrations...");
            Artisan::call('migrate');
            $this->line(Artisan::output());
        }

        return Command::SUCCESS;
    }
}
