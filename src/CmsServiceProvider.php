<?php

namespace SoipoServices\Cms;

use Illuminate\Support\Facades\Route;
use SoipoServices\Cms\Traits\GetClass;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SoipoServices\Cms\Commands\CmsCommand;

class CmsServiceProvider extends PackageServiceProvider
{

    use GetClass;

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    private function registerResources(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');

        $this->registerRoutes();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cms'),
        ], 'cms-views');

        $this->publishes([
            __DIR__.'/../config/cms.php' => config_path('cms.php'),
        ], 'cms-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'cms-migrations');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Get the Press route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'prefix' => Cms::path(),
            'namespace' => 'SoipoServices\Cms\Http\Controllers',
        ];
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('cms')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cms_table')
            ->hasCommand(CmsCommand::class)
            ->hasRoutes('web');
    }
}
