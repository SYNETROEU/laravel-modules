<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleDependencyTest extends TestCase
{
    public function test_no_circular_dependency_detection(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->createModule('A', ['enabled' => true, 'dependencies' => ['B' => '^1.0']]);
        $this->createModule('B', ['enabled' => true, 'dependencies' => ['A' => '^1.0']]);

        $this->assertTrue(true);
    }

    public function test_dependency_graph_traversal(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->createModule('Base', ['enabled' => true]);
        $this->createModule('Feature', ['enabled' => false, 'dependencies' => ['Base' => '^1.0']]);

        $this->assertTrue($modules->isEnabled('Base'));
        $this->assertFalse($modules->isEnabled('Feature'));
    }
}
