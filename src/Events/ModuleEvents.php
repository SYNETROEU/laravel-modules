<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Events;

class ModuleInstalling
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleInstalled
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleEnabling
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleEnabled
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleDisabling
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleDisabled
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleRemoving
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}

class ModuleRemoved
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}
