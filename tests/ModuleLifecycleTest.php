<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Exceptions\ModuleDependencyException;
use Synetro\LaravelModules\Exceptions\ModuleNotFoundException;

class ModuleLifecycleTest extends TestCase
{
    public function test_enable_disable_module(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);
        $path = $this->createModule('TestLifecycle', ['enabled' => false]);

        $module = $modules->find('TestLifecycle');
        $this->assertFalse($module->isEnabled());

        $modules->enable('TestLifecycle');
        $this->assertTrue($modules->isEnabled('TestLifecycle'));

        $modules->disable('TestLifecycle');
        $this->assertFalse($modules->isEnabled('TestLifecycle'));
    }

    public function test_enable_nonexistent_module_throws_exception(): void
    {
        $this->expectException(ModuleNotFoundException::class);

        $modules = $this->app->make(ModuleManagerInterface::class);
        $modules->enable('NonExistent');
    }

    public function test_module_dependencies_validated_on_enable(): void
    {
        $this->expectException(ModuleDependencyException::class);

        $modules = $this->app->make(ModuleManagerInterface::class);
        $this->createModule('Parent', ['enabled' => true]);
        $this->createModule('Child', ['enabled' => false, 'dependencies' => ['Parent' => '^1.0']]);

        $modules->enable('Child');
    }

    public function test_dependency_validation_passes_when_dependency_enabled(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);
        $this->createModule('Parent', ['enabled' => true]);
        $this->createModule('Child', ['enabled' => false, 'dependencies' => ['Parent' => '^1.0']]);

        $modules->enable('Child');

        $this->assertTrue($modules->isEnabled('Child'));
    }
}
