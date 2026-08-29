<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleApiTest extends TestCase
{
    public function test_modules_all_returns_array(): void
    {
        $this->createModule('ApiTest');
        $modules = $this->app->make(ModuleManagerInterface::class);

        $all = $modules->all();

        $this->assertIsArray($all);
        $this->assertArrayHasKey('ApiTest', $all);
    }

    public function test_modules_find_returns_module(): void
    {
        $this->createModule('ApiTest');
        $modules = $this->app->make(ModuleManagerInterface::class);

        $module = $modules->find('ApiTest');

        $this->assertNotNull($module);
        $this->assertSame('ApiTest', $module->name());
    }

    public function test_modules_has_returns_true_for_existing(): void
    {
        $this->createModule('ApiTest');
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertTrue($modules->has('ApiTest'));
        $this->assertFalse($modules->has('NonExistent'));
    }

    public function test_modules_is_enabled(): void
    {
        $this->createModule('EnabledTest', ['enabled' => true]);
        $this->createModule('DisabledTest', ['enabled' => false]);

        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertTrue($modules->isEnabled('EnabledTest'));
        $this->assertFalse($modules->isEnabled('DisabledTest'));
    }

    public function test_modules_path_returns_correct_path(): void
    {
        $this->createModule('PathTest');
        $modules = $this->app->make(ModuleManagerInterface::class);

        $path = $modules->path('PathTest');

        $this->assertNotNull($path);
        $this->assertStringContainsString('Modules/PathTest', $path);
    }
}
