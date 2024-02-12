<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Commands\DutchChamberOfCommerceApiCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_dutch-chamber-of-commerce-api_table')
            ->hasCommand(DutchChamberOfCommerceApiCommand::class);
    }
}
