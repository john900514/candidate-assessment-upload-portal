<?php

namespace App\Exceptions\Projects;

use DomainException;

class ProjectException extends DomainException
{
    public static function projectAlreadyExists(string $name) : self
    {
        return new self("{$name} already Exists!");
    }

    public static function teamAlreadyAssigned() : self
    {
        return new self("Team Already Assigned!");
    }
}
