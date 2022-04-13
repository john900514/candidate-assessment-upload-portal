<?php

namespace App\Exceptions\Candidates;

use DomainException;

class JobPositionException extends DomainException
{
    public static function jobPositionAlreadyExists(string $title) : self
    {
        return new self($title." is already exists.");
    }

    public static function assessmentAlreadyAdded() : self
    {
        return new self("Assessment Already Assigned");
    }

    public static function qualifiedRoleAlreadyAdded(string $role) : self
    {
        return new self($role." is already qualified for this job position.");
    }

    public static function candidateUserAlreadyAdded(string $email) : self
    {
        return new self($email." is already a candidate.");
    }

    public static function userCannotBeAddedToCandidates(string $why) : self
    {
        return new self($why);
    }
}
