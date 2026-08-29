<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleAboutCommand extends Command
{
    protected $signature = 'module:about';

    protected $description = 'Display module system information';

    public function handle(ModuleManagerInterface $modules): int
    {
        $this->info('Laravel Modules');
        $this->line('===============');
        $this->newLine();

        $this->info('Version: 0.1.0');
        $this->info('Environment: '.app()->environment());

        $all = $modules->all();
        $enabled = $modules->enabled();
        $disabled = $modules->disabled();

        $this->newLine();
        $this->info('Modules:');
        $this->line("  Total: ".count($all));
        $this->line("  Enabled: ".count($enabled));
        $this->line("  Disabled: ".count($disabled));

        return Command::SUCCESS;
    }
}
