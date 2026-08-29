<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Frontend;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Synetro\LaravelModules\Support\ModuleCache;

class FrontendManager
{
    protected array $pages = [];

    protected array $entryPoints = [];

    public function __construct(
        protected Filesystem $files,
        protected ModuleCache $cache,
    ) {}

    public function discover(): array
    {
        if (! empty($this->pages)) {
            return $this->pages;
        }

        $cached = $this->cache->get();

        if (! empty($cached['pages'])) {
            $this->pages = $cached['pages'];

            return $this->pages;
        }

        $directory = config('modules.directory', base_path('Modules'));

        if (! $this->files->isDirectory($directory)) {
            return [];
        }

        foreach ($this->files->directories($directory) as $modulePath) {
            $moduleName = basename($modulePath);
            $frontendPath = "{$modulePath}/Resources/js";

            if (! $this->files->isDirectory($frontendPath)) {
                continue;
            }

            $pages = $this->discoverPages($moduleName, $frontendPath);

            $this->pages[$moduleName] = $pages;
            $this->entryPoints[$moduleName] = $this->discoverEntryPoints($moduleName, $frontendPath);
        }

        return $this->pages;
    }

    public function pages(string $module): array
    {
        return $this->pages[$module] ?? [];
    }

    public function entryPoints(): array
    {
        return $this->entryPoints;
    }

    public function resolve(string $module, string $page): ?string
    {
        $pages = $this->pages[$module] ?? [];

        return $pages[$page] ?? null;
    }

    protected function discoverPages(string $module, string $frontendPath): array
    {
        $pages = [];
        $pagesDir = "{$frontendPath}/Pages";

        if (! $this->files->isDirectory($pagesDir)) {
            return $pages;
        }

        $files = $this->files->allFiles($pagesDir);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (! in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['tsx', 'jsx', 'ts', 'js'])) {
                continue;
            }

            $relativePath = $file->getRelativePathname();
            $pageName = $this->resolvePageName($relativePath);
            $pageKey = str_replace(['/', '\\'], '/', $pageName);

            $pages[$pageKey] = $module.':'.str_replace('/', '\\', $pageName);
        }

        return $pages;
    }

    protected function discoverEntryPoints(string $module, string $frontendPath): array
    {
        $entries = [];

        $possibleEntries = [
            'Resources/js/Pages/index.tsx',
            'Resources/js/Pages/index.jsx',
            'Resources/js/index.ts',
            'Resources/js/index.js',
        ];

        foreach ($possibleEntries as $entry) {
            $path = "{$frontendPath}/{$entry}";

            if ($this->files->exists($path)) {
                $entries[] = $module.'/'.ltrim($entry, 'Resources/js/');
            }
        }

        return $entries;
    }

    protected function resolvePageName(string $relativePath): string
    {
        $path = str_replace(['Pages/', 'Pages\\'], '', $relativePath);
        $path = preg_replace('/\.(tsx|jsx|ts|js)$/', '', $path);

        return $path;
    }

    public function clear(): void
    {
        $this->pages = [];
        $this->entryPoints = [];
    }

    public function register(): void
    {
        $this->discover();
    }
}
