<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Inertia;

use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Frontend\FrontendManager;

class InertiaPageResolver
{
    protected array $resolved = [];

    public function __construct(
        protected Filesystem $files,
        protected FrontendManager $frontend,
    ) {}

    public function register(): void
    {
        $pages = $this->frontend->discover();

        foreach ($pages as $module => $modulePages) {
            foreach ($modulePages as $pageKey => $pageValue) {
                $this->resolved[$pageValue] = $this->resolvePagePath($module, $pageKey);
            }
        }
    }

    public function resolve(string $page): ?string
    {
        return $this->resolved[$page] ?? null;
    }

    public function all(): array
    {
        return $this->resolved;
    }

    protected function resolvePagePath(string $module, string $pageKey): string
    {
        $directory = config('modules.directory', base_path('Modules'));
        $parts = explode('/', $pageKey);
        $filename = array_pop($parts);

        $path = $directory.'/'.$module.'/Resources/js/pages';

        foreach ($parts as $part) {
            $path .= '/'.$part;
        }

        $extensions = ['tsx', 'jsx', 'ts', 'js'];

        foreach ($extensions as $ext) {
            $candidate = $path.'/'.$filename.'.'.$ext;

            if ($this->files->exists($candidate)) {
                return $candidate;
            }
        }

        return $path.'/'.$filename.'.tsx';
    }
}
