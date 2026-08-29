<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleMakeCommand extends Command
{
    protected $signature = 'module:make
                            {name : The module name}
                            {--full : Generate full module}
                            {--inertia : Include Inertia.js scaffolding}
                            {--api : Generate API routes and controllers}
                            {--force : Force overwrite}';

    protected $description = 'Create a new module';

    public function handle(ModuleManagerInterface $modules, Filesystem $files): int
    {
        $name = $this->argument('name');
        $slug = strtolower($name);
        $directory = config('modules.directory', base_path('Modules'));
        $modulePath = "{$directory}/{$name}";

        if ($files->exists($modulePath) && ! $this->option('force')) {
            $this->error("Module [{$name}] already exists.");

            return Command::FAILURE;
        }

        $this->info("Creating module: {$name}");

        $this->createStructure($modulePath, $files);

        $this->createModuleJson($name, $slug, $modulePath, $files);

        $this->createServiceProvider($name, $modulePath, $files);

        if ($this->option('full') || $this->option('inertia')) {
            $this->createInertiaScaffolding($name, $modulePath, $files);
        }

        if ($this->option('full') || $this->option('api')) {
            $this->createApiScaffolding($name, $modulePath, $files);
        }

        $this->info("Module [{$name}] created successfully!");
        $this->line('');
        $this->line('Next steps:');
        $this->line("  1. Run: php artisan module:enable {$name}");
        $this->line("  2. Visit: /{$slug}");

        return Command::SUCCESS;
    }

    protected function createStructure(string $modulePath, Filesystem $files): void
    {
        $structure = config('modules.structure', []);

        foreach ($structure as $dir) {
            $files->ensureDirectoryExists("{$modulePath}/{$dir}");
        }
    }

    protected function createModuleJson(string $name, string $slug, string $modulePath, Filesystem $files): void
    {
        $namespace = config('modules.namespace', 'Modules');
        $provider = "{$namespace}\\{$name}\\ModuleServiceProvider";

        $content = json_encode([
            'name' => $name,
            'slug' => $slug,
            'description' => '',
            'version' => '1.0.0',
            'provider' => $provider,
            'enabled' => true,
            'dependencies' => [],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $files->put("{$modulePath}/module.json", $content);
        $this->info("  ✓ module.json");
    }

    protected function createServiceProvider(string $name, string $modulePath, Filesystem $files): void
    {
        $namespace = config('modules.namespace', 'Modules');
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace}\\{$name};

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
PHP;

        $files->put("{$modulePath}/ModuleServiceProvider.php", $content);
        $this->info("  ✓ ModuleServiceProvider.php");
    }

    protected function createInertiaScaffolding(string $name, string $modulePath, Filesystem $files): void
    {
        $this->createPage($modulePath, 'Index.tsx', $name, $files);
        $this->createPage($modulePath, 'Create.tsx', $name, $files);
        $this->createComponent($modulePath, 'ExampleCard.tsx', $files);
    }

    protected function createApiScaffolding(string $name, string $modulePath, Filesystem $files): void
    {
        $namespace = config('modules.namespace', 'Modules');
        $controllerPath = "{$modulePath}/Http/Controllers/{$name}Controller.php";

        $content = <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace}\\{$name}\\Http\\Controllers;

use Illuminate\\Http\\JsonResponse;

class {$name}Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Hello from {$name}']);
    }
}
PHP;

        $files->put($controllerPath, $content);
        $this->info("  ✓ Http/Controllers/{$name}Controller.php");

        $routesPath = "{$modulePath}/Routes/api.php";

        $routesContent = <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;
use {$namespace}\\{$name}\\Http\\Controllers\\{$name}Controller;

Route::get('/', {$name}Controller::class.'@index');
PHP;

        $files->put($routesPath, $routesContent);
        $this->info("  ✓ Routes/api.php");
    }

    protected function createPage(string $modulePath, string $filename, string $moduleName, Filesystem $files): void
    {
        $pagesDir = "{$modulePath}/Resources/js/Pages";

        $content = <<<TSX
import { Head, Link } from '@inertiajs/react';

export default function {$filename.':'}() {
    return (
        <div className="p-6">
            <Head title="{$moduleName}" />

            <h1 className="text-2xl font-bold">{$moduleName} Module</h1>

            <p className="mt-4 text-gray-600">
                Welcome to the {$moduleName} module.
            </p>

            <Link
                href="/"
                className="mt-6 inline-block rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Go Home
            </Link>
        </div>
    );
}
TSX;

        $files->put("{$pagesDir}/{$filename}", $content);
        $this->info("  ✓ Resources/js/Pages/{$filename}");
    }

    protected function createComponent(string $modulePath, string $filename, Filesystem $files): void
    {
        $componentsDir = "{$modulePath}/Resources/js/Components";

        $content = <<<TSX
interface Props {
    title: string;
    description?: string;
}

export default function {$filename.':'}({ title, description }: Props) {
    return (
        <div className="rounded border p-4">
            <h3 className="font-semibold">{title}</h3>
            {description && <p className="mt-1 text-gray-600">{description}</p>}
        </div>
    );
}
TSX;

        $files->put("{$componentsDir}/{$filename}", $content);
        $this->info("  ✓ Resources/js/Components/{$filename}");
    }
}
