<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleRemoveCommand extends Command
{
    protected $signature = 'module:remove
                            {name : The module name}
                            {--force : Skip confirmation}
                            {--keep-data : Keep database data}';

    protected $description = 'Remove a module';

    public function handle(ModuleManagerInterface $modules, Filesystem $files): int
    {
        $name = $this->argument('name');
        $module = $modules->find($name);

        if ($module === null) {
            $this->error("Module [{$name}] not found.");

            return Command::FAILURE;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to remove module [{$name}]?")) {
                return Command::SUCCESS;
            }
        }

        $this->info("Removing module [{$name}]...");

        if ($this->option('keep-data')) {
            $this->info("  Keeping database data.");
        }

        $files->deleteDirectory($module->path());

        $this->info("Module [{$name}] removed.");

        return Command::SUCCESS;
    }
}
