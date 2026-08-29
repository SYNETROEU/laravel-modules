<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleListCommand extends Command
{
    protected $signature = 'module:list';

    protected $description = 'List all modules';

    public function handle(ModuleManagerInterface $modules): int
    {
        $all = $modules->all();

        if (empty($all)) {
            $this->info('No modules found.');
            return Command::SUCCESS;
        }

        $rows = [];

        foreach ($all as $module) {
            $rows[] = [
                $module->name(),
                $module->version() ?? 'N/A',
                $module->isEnabled() ? 'ENABLED' : 'DISABLED',
                implode(', ', array_keys($module->dependencies())) ?: '-',
            ];
        }

        $this->info('Modules');
        $this->line(str_repeat('=', 60));
        $this->table(['Name', 'Version', 'Status', 'Dependencies'], $rows);

        return Command::SUCCESS;
    }
}
