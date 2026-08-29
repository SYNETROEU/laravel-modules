<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Tests;

use Synetro\LaravelModules\Frontend\FrontendManager;
use Synetro\LaravelModules\Inertia\InertiaPageResolver;

class FrontendDiscoveryTest extends TestCase
{
    public function test_discovers_frontend_pages(): void
    {
        $path = $this->modulesPath().'/Blog/Resources/js/Pages';
        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $filesystem->ensureDirectoryExists($path);
        $filesystem->ensureDirectoryExists($path.'/Posts');
        file_put_contents($path.'/Index.tsx', '<div>Blog Index</div>');
        file_put_contents($path.'/Posts/Show.tsx', '<div>Post Show</div>');

        $manager = $this->app->make(FrontendManager::class);
        $pages = $manager->discover();

        $this->assertArrayHasKey('Blog', $pages);
        $this->assertArrayHasKey('Index', $pages['Blog']);
        $this->assertArrayHasKey('Posts/Show', $pages['Blog']);
    }

    public function test_inertia_page_resolver(): void
    {
        $path = $this->modulesPath().'/Blog/Resources/js/Pages';
        (new \Illuminate\Filesystem\Filesystem())->ensureDirectoryExists($path);
        file_put_contents($path.'/Index.tsx', '<div>Blog Index</div>');

        $resolver = $this->app->make(InertiaPageResolver::class);
        $resolver->register();

        $page = $resolver->resolve('Blog:Index');

        $this->assertNotNull($page);
        $this->assertStringContainsString('Blog/Resources/js/Pages/Index.tsx', $page);
    }
}
