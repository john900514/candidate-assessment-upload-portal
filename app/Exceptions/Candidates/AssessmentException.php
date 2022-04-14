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
        return new self('Source Code already assign. Please remove first.');
    }
}
