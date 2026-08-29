<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Modules;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Events\ModuleEnabled;
use Synetro\LaravelModules\Events\ModuleEnabling;
use Synetro\LaravelModules\Events\ModuleDisabling;
use Synetro\LaravelModules\Events\ModuleDisabled;
use Synetro\LaravelModules\Events\ModuleInstalling;
use Synetro\LaravelModules\Events\ModuleInstalled;
use Synetro\LaravelModules\Events\ModuleRemoving;
use Synetro\LaravelModules\Events\ModuleRemoved;
use Synetro\LaravelModules\Exceptions\CircularDependencyException;
use Synetro\LaravelModules\Exceptions\ModuleDependencyException;
use Synetro\LaravelModules\Exceptions\ModuleNotFoundException;
use Synetro\LaravelModules\Exceptions\ModuleAlreadyEnabledException;
use Synetro\LaravelModules\Exceptions\ModuleAlreadyDisabledException;
use Synetro\LaravelModules\Support\ModuleCache;

class ModuleManager implements ModuleManagerInterface
{
    protected array $modules = [];

    protected array $enabledModules = [];

    protected array $disabledModules = [];

    protected bool $discovered = false;

    public function __construct(
        protected Filesystem $files,
        protected ModuleCache $cache,
    ) {}

    public function clear(): void
    {
        $this->modules = [];
        $this->enabledModules = [];
        $this->disabledModules = [];
        $this->discovered = false;
    }

    public function discover(): void
    {
        if ($this->discovered) {
            return;
        }

        $cached = $this->cache->get();

        if (! empty($cached)) {
            $this->loadFromCache();

            return;
        }

        $directory = config('modules.directory', base_path('Modules'));
        $namespace = config('modules.namespace', 'Modules');

        if (! $this->files->isDirectory($directory)) {
            $this->discovered = true;
            return;
        }

        $modules = [];

        foreach ($this->files->directories($directory) as $modulePath) {
            $moduleName = basename($modulePath);
            $metadata = $this->loadMetadata($modulePath);

            if ($metadata === null) {
                continue;
            }

            $provider = $metadata['provider'] ?? "{$namespace}\\{$moduleName}\\ModuleServiceProvider";

            $module = Module::fromMetadata($modulePath, array_merge($metadata, [
                'provider' => $provider,
            ]));

            $modules[$moduleName] = $module;
        }

        $this->modules = $modules;
        $this->categorizeModules();

        if (config('modules.cache', true)) {
            $this->cache->put($this->modulesToArrays($modules));
        }

        $this->discovered = true;
    }

    protected function modulesToArrays(array $modules): array
    {
        $arrays = [];

        foreach ($modules as $name => $module) {
            $arrays[$name] = $module->toArray();
        }

        return $arrays;
    }

    protected function loadFromCache(): void
    {
        $cached = $this->cache->get();

        if (empty($cached)) {
            return;
        }

        $modules = [];

        foreach ($cached as $name => $data) {
            $modules[$name] = Module::fromMetadata($data['path'], $data);
        }

        $this->modules = $modules;
        $this->categorizeModules();
        $this->discovered = true;
    }

    public function all(): array
    {
        $this->discover();

        return $this->modules;
    }

    public function enabled(): array
    {
        $this->discover();

        return $this->enabledModules;
    }

    public function disabled(): array
    {
        $this->discover();

        return $this->disabledModules;
    }

    public function find(string $name): ?Module
    {
        $this->discover();

        return $this->modules[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return $this->find($name) !== null;
    }

    public function isEnabled(string $name): bool
    {
        $module = $this->find($name);

        return $module !== null && $module->isEnabled();
    }

    public function enable(string $name): void
    {
        $this->discover();

        $module = $this->find($name);

        if ($module === null) {
            throw new ModuleNotFoundException("Module [{$name}] not found.");
        }

        if ($module->isEnabled()) {
            throw new ModuleAlreadyEnabledException("Module [{$name}] is already enabled.");
        }

        event(new ModuleEnabling($module));

        $this->validateDependencies($module);

        $modulePath = $module->path();
        $metadataPath = "{$modulePath}/module.json";

        $metadata = $this->loadMetadata($modulePath) ?? [];
        $metadata['enabled'] = true;

        $this->files->put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->modules[$name] = Module::fromMetadata($modulePath, $metadata);
        $this->categorizeModules();

        event(new ModuleEnabled($module));
    }

    public function disable(string $name): void
    {
        $this->discover();

        $module = $this->find($name);

        if ($module === null) {
            throw new ModuleNotFoundException("Module [{$name}] not found.");
        }

        if (! $module->isEnabled()) {
            throw new ModuleAlreadyDisabledException("Module [{$name}] is already disabled.");
        }

        $dependent = $this->findDependents($name);

        if (! empty($dependent)) {
            $names = implode(', ', array_map(fn ($m) => $m->name(), $dependent));
            throw new ModuleDependencyException("Cannot disable [{$name}]. Required by: {$names}");
        }

        event(new ModuleDisabling($module));

        $modulePath = $module->path();
        $metadataPath = "{$modulePath}/module.json";

        $metadata = $this->loadMetadata($modulePath) ?? [];
        $metadata['enabled'] = false;

        $this->files->put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->modules[$name] = Module::fromMetadata($modulePath, $metadata);
        $this->categorizeModules();

        event(new ModuleDisabled($module));
    }

    public function path(string $name): ?string
    {
        $module = $this->find($name);

        return $module?->path();
    }

    public function routes(string $name): ?string
    {
        $module = $this->find($name);

        return $module?->routes();
    }

    public function config(string $name, mixed $default = null): mixed
    {
        $module = $this->find($name);

        if ($module === null) {
            return $default;
        }

        $configPath = $module->path().'/Config/config.php';

        if (! $this->files->exists($configPath)) {
            return $default;
        }

        return require $configPath;
    }

    protected function loadMetadata(string $modulePath): ?array
    {
        $metadataPath = "{$modulePath}/module.json";

        if (! $this->files->exists($metadataPath)) {
            return null;
        }

        $content = $this->files->get($metadataPath);
        $metadata = json_decode($content, true);

        if (! is_array($metadata)) {
            return null;
        }

        return $metadata;
    }

    protected function categorizeModules(): void
    {
        $this->enabledModules = [];
        $this->disabledModules = [];

        foreach ($this->modules as $name => $module) {
            if ($module->isEnabled()) {
                $this->enabledModules[$name] = $module;
            } else {
                $this->disabledModules[$name] = $module;
            }
        }
    }

    protected function validateDependencies(Module $module): void
    {
        $dependencies = $module->dependencies();

        foreach ($dependencies as $dependency => $constraint) {
            if (! $this->has($dependency)) {
                throw new ModuleDependencyException(
                    "Module [{$module->name()}] requires [{$dependency}] which is not installed."
                );
            }

            if (! $this->isEnabled($dependency)) {
                throw new ModuleDependencyException(
                    "Module [{$module->name()}] requires [{$dependency}] which is disabled. Run: php artisan module:enable {$dependency}"
                );
            }
        }
    }

    protected function findDependents(string $name): array
    {
        $dependents = [];

        foreach ($this->modules as $module) {
            if (isset($module->dependencies()[$name])) {
                $dependents[] = $module;
            }
        }

        return $dependents;
    }
}
