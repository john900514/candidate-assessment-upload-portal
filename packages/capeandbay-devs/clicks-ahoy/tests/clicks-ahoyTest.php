<?php

namespace capeandbay-devs\clicks-ahoy\Tests;

use capeandbay-devs\clicks-ahoy\Facades\clicks-ahoy;
use capeandbay-devs\clicks-ahoy\ServiceProvider;
use Orchestra\Testbench\TestCase;

class clicks-ahoyTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'clicks-ahoy' => clicks-ahoy::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
