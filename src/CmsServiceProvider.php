<?php

namespace SoipoServices\Cms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SoipoServices\Cms\Commands\CmsCommand;

class CmsServiceProvider extends PackageServiceProvider
{
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
            ->hasCommand(CmsCommand::class);
    }
}
