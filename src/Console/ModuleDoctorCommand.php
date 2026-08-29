<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleDoctorCommand extends Command
{
    protected $signature = 'module:doctor';

    protected $description = 'Diagnose module health';

    public function handle(ModuleManagerInterface $modules): int
    {
        $checks = [];

        $modulesList = $modules->all();

        if (empty($modulesList)) {
            $this->info('No modules found.');
            return Command::SUCCESS;
        }

        $checks[] = ['name' => 'Modules', 'status' => 'pass', 'message' => count($modulesList).' found'];

        foreach ($modulesList as $name => $module) {
            $checks[] = [
                'name' => $module->name(),
                'status' => $module->isEnabled() ? 'pass' : 'warn',
                'message' => $module->isEnabled() ? 'Enabled' : 'Disabled',
            ];
        }

        $rows = collect($checks)->map(function ($c) {
            $status = match ($c['status']) {
                'pass' => 'OK',
                'warn' => 'WARN',
                default => 'FAIL',
            };

            return [
                $c['name'],
                $status,
                $c['message'],
            ];
        })->toArray();

        $this->table(['Module', 'Status', 'Message'], $rows);

        return Command::SUCCESS;
    }
}
