<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Exceptions\ModuleAlreadyDisabledException;
use Synetro\LaravelModules\Exceptions\ModuleNotFoundException;

class ModuleDisableCommand extends Command
{
    protected $signature = 'module:disable {name : The module name}';

    protected $description = 'Disable a module';

    public function handle(ModuleManagerInterface $modules): int
    {
        $name = $this->argument('name');

        try {
            $modules->disable($name);
            $this->info("Module [{$name}] disabled.");
        } catch (ModuleNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        } catch (ModuleAlreadyDisabledException $e) {
            $this->warn($e->getMessage());

            return Command::SUCCESS;
        }

        return Command::SUCCESS;
    }
}
