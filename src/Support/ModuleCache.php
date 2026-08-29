<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;

class ModuleCache
{
    public function __construct(
        protected Filesystem $files,
    ) {}

    public function get(): array
    {
        $path = config('modules.manifest', storage_path('framework/cache/modules.php'));

        if (! $this->files->exists($path)) {
            return [];
        }

        return require $path;
    }

    public function put(array $modules): void
    {
        if (! config('modules.cache', true)) {
            return;
        }

        $path = config('modules.manifest', storage_path('framework/cache/modules.php'));

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put($path, '<?php return '.var_export($modules, true).';'.PHP_EOL);
    }

    public function clear(): void
    {
        $path = config('modules.manifest', storage_path('framework/cache/modules.php'));

        if ($this->files->exists($path)) {
            $this->files->delete($path);
        }
    }
}
