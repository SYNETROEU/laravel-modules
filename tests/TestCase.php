<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Synetro\LaravelModules\Providers\LaravelModulesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected ?string $tempBase = null;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelModulesServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('modules.directory', $this->modulesPath());
        $app['config']->set('modules.namespace', 'Modules');
        $app['config']->set('modules.cache', false);
        $app['config']->set('modules.manifest', $this->tempPath('modules.php'));
    }

    protected function modulesPath(): string
    {
        return $this->tempPath('Modules');
    }

    protected function tempPath(string $path = ''): string
    {
        if ($this->tempBase === null) {
            $this->tempBase = sys_get_temp_dir().'/laravel-modules-'.uniqid();
        }

        if ($path) {
            return $this->tempBase.'/'.$path;
        }

        return $this->tempBase;
    }

    protected function createModule(string $name, array $metadata = []): string
    {
        $filesystem = new Filesystem();
        $path = $this->modulesPath().'/'.$name;

        $filesystem->ensureDirectoryExists($path);
        $filesystem->ensureDirectoryExists($path.'/Resources/js/Pages');
        $filesystem->ensureDirectoryExists($path.'/Routes');

        $defaultMetadata = [
            'name' => $name,
            'slug' => strtolower($name),
            'description' => 'Test module',
            'version' => '1.0.0',
            'provider' => 'Modules\\'.$name.'\\ModuleServiceProvider',
            'enabled' => $metadata['enabled'] ?? true,
            'dependencies' => $metadata['dependencies'] ?? [],
        ];

        $filesystem->put($path.'/module.json', json_encode(array_merge($defaultMetadata, $metadata), JSON_PRETTY_PRINT));

        return $path;
    }

    protected function removeModule(string $name): void
    {
        $filesystem = new Filesystem();
        $path = $this->modulesPath().'/'.$name;

        if ($filesystem->exists($path)) {
            $filesystem->deleteDirectory($path);
        }
    }
}
