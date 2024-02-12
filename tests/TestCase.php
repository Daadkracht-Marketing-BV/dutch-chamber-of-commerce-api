<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Tests;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\DutchChamberOfCommerceApiServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DaadkrachtMarketing\\DutchChamberOfCommerceApi\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            DutchChamberOfCommerceApiServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_dutch-chamber-of-commerce-api_table.php.stub';
        $migration->up();
        */
    }
}
