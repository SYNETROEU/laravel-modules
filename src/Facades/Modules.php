<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Facades;

use Illuminate\Support\Facades\Facade;

class Modules extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Synetro\LaravelModules\Contracts\ModuleManagerInterface::class;
    }
}
