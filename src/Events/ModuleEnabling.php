<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Events;

class ModuleEnabling
{
    public function __construct(public readonly \Synetro\LaravelModules\Modules\Module $module) {}
}
