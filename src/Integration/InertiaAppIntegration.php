<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Integration;

use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Modules\Module;

class InertiaAppIntegration
{
    public function __construct(protected Filesystem $files) {}

    public function install(Module $module): void
    {
        $this->patchComposerAutoload();
        $this->patchAppTsx();
        $this->patchAppBlade();
    }

    protected function patchComposerAutoload(): void
    {
        $path = base_path('composer.json');

        if (! $this->files->exists($path)) {
            return;
        }

        $composer = json_decode($this->files->get($path), true);

        if (! is_array($composer)) {
            return;
        }

        $autoload = $composer['autoload'] ?? [];

        if (! isset($autoload['classmap'])) {
            $autoload['classmap'] = [];
        }

        $classmap = $autoload['classmap'];

        if (in_array('Modules', $classmap, true)) {
            return;
        }

        $classmap[] = 'Modules';
        $autoload['classmap'] = $classmap;
        $composer['autoload'] = $autoload;

        $this->files->put($path, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
    }

    protected function patchAppTsx(): void
    {
        $path = base_path('resources/js/app.tsx');

        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        if (str_contains($content, "name.includes('::')")) {
            return;
        }

        $needle = "void createInertiaApp({\n    title:";

        if (! str_contains($content, $needle)) {
            $needle = "void createInertiaApp({\r\n    title:";
        }

        if (! str_contains($content, $needle)) {
            return;
        }

        $replacement = "void createInertiaApp({\n    resolve: (name) => {\n        if (name.includes('::')) {\n            const [module, ...pageParts] = name.split('::');\n            const pagePath = pageParts.join('/');\n\n            return import(`../../Modules/\${module}/Resources/js/pages/\${pagePath}`);\n        }\n\n        return import(`./pages/\${name}`);\n    },\n    title:";

        $content = str_replace($needle, $replacement, $content);

        $this->files->put($path, $content);
    }

    protected function patchAppBlade(): void
    {
        $path = base_path('resources/views/app.blade.php');

        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        if (str_contains($content, "str_contains(\$page['component'], '::')")) {
            return;
        }

        $needle = "@vite(['resources/css/app.css', 'resources/js/app.tsx', \"resources/js/pages/{\$page['component']}.tsx\"])";

        if (! str_contains($content, $needle)) {
            return;
        }

        $replacement = "@vite(['resources/css/app.css', 'resources/js/app.tsx'])\n        @if (! str_contains(\$page['component'], '::'))\n            @vite(\"resources/js/pages/{\$page['component']}.tsx\")\n        @endif";

        $content = str_replace($needle, $replacement, $content);

        $this->files->put($path, $content);
    }
}

