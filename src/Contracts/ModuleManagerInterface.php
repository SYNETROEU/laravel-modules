<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Contracts;

use Synetro\LaravelModules\Modules\Module;

interface ModuleManagerInterface
{
    public function discover(): void;

    public function all(): array;

    public function enabled(): array;

    public function disabled(): array;

    public function find(string $name): ?Module;

    public function has(string $name): bool;

    public function isEnabled(string $name): bool;

    public function enable(string $name): void;

    public function disable(string $name): void;

    public function path(string $name): ?string;

    public function routes(string $name): ?string;

    public function config(string $name, mixed $default = null): mixed;
}
