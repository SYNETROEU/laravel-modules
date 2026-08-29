<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Synetro\LaravelModules\Support\ModuleCache;

class ModuleBuildCommand extends Command
{
    protected $signature = 'module:build';

    protected $description = 'Build module manifests and caches';

    public function handle(ModuleCache $cache): int
    {
        $this->info('Building modules...');

        $cache->clear();

        $this->info('Manifest cleared.');

        return Command::SUCCESS;
    }
}
