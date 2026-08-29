<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Contracts\ModuleManagerInterface;

class ModuleSecurityTest extends TestCase
{
    public function test_path_traversal_prevention(): void
    {
        $modules = $this->app->make(ModuleManagerInterface::class);

        $this->assertNull($modules->find('../etc/passwd'));
        $this->assertNull($modules->find('../../../etc/passwd'));
        $this->assertFalse($modules->has('../etc'));
    }

    public function test_module_name_normalization(): void
    {
        $this->createModule('NormalModule');

        $modules = $this->app->make(ModuleManagerInterface::class);
        $modules->clear();

        $this->assertTrue($modules->has('NormalModule'));
    }
}
