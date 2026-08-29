<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Events\ModuleEnabled;
use Synetro\LaravelModules\Events\ModuleEnabling;
use Synetro\LaravelModules\Exceptions\ModuleDependencyException;
use Synetro\LaravelModules\Exceptions\ModuleNotFoundException;

class ModuleEnableCommand extends Command
{
    protected $signature = 'module:enable {name : The module name}';

    protected $description = 'Enable a module';

    public function handle(ModuleManagerInterface $modules): int
    {
        $name = $this->argument('name');

        try {
            $modules->enable($name);
            $this->info("Module [{$name}] enabled.");
        } catch (ModuleNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        } catch (ModuleDependencyException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
