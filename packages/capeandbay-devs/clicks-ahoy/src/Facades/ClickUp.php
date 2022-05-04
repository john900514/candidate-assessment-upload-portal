<?php

namespace CapeAndBay\ClicksAhoy\Facades;

use Illuminate\Support\Facades\Facade;

class ClickUp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CapeAndBay\ClicksAhoy\ClickUp::class;
    }
}
