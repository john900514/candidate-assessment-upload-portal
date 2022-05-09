<?php

namespace App\Exceptions\Assets;

use Exception;

class QuizException extends Exception
{
    public static function quizAlreadyCreated(string $name) : self
    {
        return new self("A quiz named {$name} already exists!");
    }
}
