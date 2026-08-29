<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleTestCommand extends Command
{
    protected $signature = 'module:test {name? : The module name}';

    protected $description = 'Run module tests';

    public function handle(ModuleManagerInterface $modules): int
    {
        $name = $this->argument('name');

        if ($name) {
            $module = $modules->find($name);

            if ($module === null) {
                $this->error("Module [{$name}] not found.");

                return Command::FAILURE;
            }

            $testsPath = $module->testsPath();

            if ($testsPath === null) {
                $this->warn("Module [{$name}] has no tests.");

                return Command::SUCCESS;
            }

            $this->info("Running tests for [{$name}]...");
            Artisan::call('test', ['--path' => $testsPath]);
            $this->line(Artisan::output());
        } else {
            $this->info("Running all module tests...");
            Artisan::call('test');
            $this->line(Artisan::output());
        }

        return Command::SUCCESS;
    }
}
