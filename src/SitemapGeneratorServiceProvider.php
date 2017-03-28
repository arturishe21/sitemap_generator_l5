<?php namespace Vis\SitemapGenerator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class SitemapGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . '/../vendor/autoload.php';

        $this->setupRoutes($this->app->router);

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/sitemap-generator'),
        ], 'sitemap-generator-view');

        $this->publishes([
            __DIR__ . '/config' => config_path('sitemap-generator/')
        ], 'sitemap-generator-config');

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/Routes/routes.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function provides()
    {
    }
}



