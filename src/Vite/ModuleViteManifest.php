<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Vite;

use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Frontend\FrontendManager;

class ModuleViteManifest
{
    protected array $manifest = [];

    public function __construct(
        protected Filesystem $files,
        protected FrontendManager $frontend,
    ) {}

    public function build(): array
    {
        $pages = $this->frontend->discover();
        $manifest = [];

        foreach ($pages as $module => $modulePages) {
            foreach ($modulePages as $pageKey => $pageValue) {
                $entry = $this->resolveEntryPoint($module, $pageKey);
                $manifest[$pageValue] = $entry;
            }
        }

        $this->manifest = $manifest;

        return $manifest;
    }

    public function get(string $page): ?string
    {
        return $this->manifest[$page] ?? null;
    }

    public function all(): array
    {
        return $this->manifest;
    }

    protected function resolveEntryPoint(string $module, string $pageKey): string
    {
        $parts = explode('/', $pageKey);
        $filename = array_pop($parts);

        $path = 'Modules/'.$module.'/Resources/js/Pages';

        foreach ($parts as $part) {
            $path .= '/'.$part;
        }

        return $path.'/'.$filename;
    }
}
