<?php

namespace App\Exceptions\Clients;

use DomainException;

class ClientException extends DomainException
{
    public static function clientAlreadyExists(string $name)
    {
        return new self("{$name} already Exists!");
    }
}
