<?php

namespace App\Exceptions\Teams;

use DomainException;

class TeamException extends DomainException
{
    public static function teamAlreadyExists(string $name) : self
    {
        return new self("{$name} already exists!");
    }

    public static function projectAlreadyAssigned() : self
    {
        return new self("Project already assigned!");
    }
}
