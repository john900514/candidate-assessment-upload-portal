<?php

namespace App\Exceptions\Assets;

use DomainException;

class QuizException extends DomainException
{
    public static function quizAlreadyCreated(string $name) : self
    {
        return new self("A quiz named {$name} already exists!");
    }

    public static function quizAlreadyActivated() : self
    {
        return new self("This quiz is already activated!");
    }

    public static function quizAlreadyDeactivated() : self
    {
        return new self("This quiz is not active!");
    }

    public static function questionAlreadyAdded() : self
    {
        return new self("This question is already attached to this quiz!");
    }
}
