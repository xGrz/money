<?php

namespace XGrz\Money\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use XGrz\Money\MoneyServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MoneyServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors', 1);
        parent::setUp();

        // $this->artisan('migrate'); // no migrations in this package
    }

}