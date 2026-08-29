<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Modules;

use Synetro\LaravelModules\Exceptions\InvalidModuleMetadataException;

class Module
{
    protected array $cached = [];

    public function __construct(
        protected string $name,
        protected string $slug,
        protected string $path,
        protected string $provider,
        protected array $metadata = [],
    ) {}

    public static function fromMetadata(string $path, array $metadata): self
    {
        $name = $metadata['name'] ?? basename($path);
        $slug = $metadata['slug'] ?? strtolower(basename($path));
        $provider = $metadata['provider'] ?? '';

        if (! $name) {
            throw new InvalidModuleMetadataException("Module name is required in {$path}/module.json");
        }

        return new self(
            name: $name,
            slug: $slug,
            path: $path,
            provider: $provider,
            metadata: $metadata,
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function provider(): ?string
    {
        return $this->provider ?: null;
    }

    public function version(): ?string
    {
        return $this->metadata['version'] ?? null;
    }

    public function description(): ?string
    {
        return $this->metadata['description'] ?? null;
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->metadata['enabled'] ?? true);
    }

    public function dependencies(): array
    {
        return $this->metadata['dependencies'] ?? [];
    }

    public function routes(): ?string
    {
        $routesPath = $this->path.'/Routes';

        if (is_dir($routesPath)) {
            return $routesPath;
        }

        return null;
    }

    public function configPath(): ?string
    {
        $configPath = $this->path.'/Config';

        if (is_dir($configPath)) {
            return $configPath;
        }

        return null;
    }

    public function migrationsPath(): ?string
    {
        $migrationsPath = $this->path.'/Database/migrations';

        if (is_dir($migrationsPath)) {
            return $migrationsPath;
        }

        return null;
    }

    public function langPath(): ?string
    {
        $langPath = $this->path.'/Resources/lang';

        if (is_dir($langPath)) {
            return $langPath;
        }

        return null;
    }

    public function viewsPath(): ?string
    {
        $viewsPath = $this->path.'/Resources/views';

        if (is_dir($viewsPath)) {
            return $viewsPath;
        }

        return null;
    }

    public function frontendPath(): ?string
    {
        $frontendPath = $this->path.'/Resources/js';

        if (is_dir($frontendPath)) {
            return $frontendPath;
        }

        return null;
    }

    public function testsPath(): ?string
    {
        $testsPath = $this->path.'/Tests';

        if (is_dir($testsPath)) {
            return $testsPath;
        }

        return null;
    }

    public function metadata(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'path' => $this->path,
            'provider' => $this->provider,
            'version' => $this->version(),
            'description' => $this->description(),
            'enabled' => $this->isEnabled(),
            'dependencies' => $this->dependencies(),
        ];
    }
}
