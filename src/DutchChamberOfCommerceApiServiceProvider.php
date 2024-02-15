<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DutchChamberOfCommerceApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('dutch-chamber-of-commerce-api')
            ->hasConfigFile();
    }
}
