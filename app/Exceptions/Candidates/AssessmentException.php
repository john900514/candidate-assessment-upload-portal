<?php

namespace App\Exceptions\Candidates;

use DomainException;

class AssessmentException extends DomainException
{
    public static function assessmentAlreadyCreated() : self
    {
        return new self('Assessment already created!');
    }

    public static function cannotAddSourceCode() : self
    {
        return new self('Source Code Flag Not Toggled.');
    }

    public static function sourceCodeAlreadyAdded() : self
    {
        return new self('Source Code already assigned. Please remove first.');
    }

    public static function quizAlreadyLinked()
    {
        return new self('Quiz is already Linked to assessment. Please remove first.');
    }

    public static function quizNotLinked()
    {
        return new self('Quiz is NOT Linked to assessment. Please add first.');
    }
}
