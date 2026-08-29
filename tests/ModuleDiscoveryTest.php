<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Contracts\ModuleManagerInterface;
use Synetro\LaravelModules\Modules\Module;

class ModuleDiscoveryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createModule('Blog');
        $this->createModule('Ecommerce');
    }

    public function test_discovers_modules(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertCount(2, $modules->all());
    }

    public function test_finds_module_by_name(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertInstanceOf(Module::class, $modules->find('Blog'));
        $this->assertNull($modules->find('NonExistent'));
    }

    public function test_module_has_correct_name(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);
        $blog = $modules->find('Blog');

        $this->assertSame('Blog', $blog->name());
    }

    public function test_module_has_correct_path(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);
        $blog = $modules->find('Blog');

        $this->assertStringContainsString('Modules/Blog', $blog->path());
    }

    public function test_enabled_modules_returns_only_enabled(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertCount(2, $modules->enabled());
    }

    public function test_disabled_module_excluded_from_enabled(): void
    {
        $this->createModule('DisabledModule', ['enabled' => false]);

        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertCount(3, $modules->all());
        $this->assertCount(2, $modules->enabled());
        $this->assertCount(1, $modules->disabled());
    }
}
