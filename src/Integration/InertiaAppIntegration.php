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
        $this->ensureResolveInAppTsx();
        $this->ensureModuleViteDirectiveInBlade();
    }

    protected function ensureResolveInAppTsx(): void
    {
        $path = base_path('resources/js/app.tsx');

        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        if (str_contains($content, 'name.includes(\'::\')')) {
            return;
        }

        $content = str_replace(
            "void createInertiaApp({\n    title:",
            "void createInertiaApp({\n    resolve: (name) => {\n        if (name.includes('::')) {\n            const [module, ...pageParts] = name.split('::');\n            const pagePath = pageParts.join('/');\n\n            return import(`../Modules/${module}/Resources/js/pages/${pagePath}`);\n        }\n\n        return import(`../pages/${name}`);\n    },\n    title:",
            $content
        );

        $this->files->put($path, $content);
    }

    protected function ensureModuleViteDirectiveInBlade(): void
    {
        $path = base_path('resources/views/app.blade.php');

        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        if (str_contains($content, "str_contains(\$page['component'], '::')")) {
            return;
        }

        $content = str_replace(
            "@vite(['resources/css/app.css', 'resources/js/app.tsx', \"resources/js/pages/{$page['component']}.tsx\"])",
            "@vite(['resources/css/app.css', 'resources/js/app.tsx'])\n        @if (! str_contains(\$page['component'], '::'))\n            @vite(\"resources/js/pages/{$page['component']}.tsx\")\n        @endif",
            $content
        );

        $this->files->put($path, $content);
    }
}
