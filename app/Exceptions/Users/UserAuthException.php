<?php

namespace App\Exceptions\Users;

use DomainException;

class UserAuthException extends DomainException
{
    public static function userAlreadyExists(string $email) : self
    {
        return new self("User {$email} already exists");
    }

    public static function userAlreadyVerified() : self
    {
        return new self("User is already verified. No more welcome emails can be sent.");
    }

    public static function accessTokenPermissionDenied() : self
    {
        return new self("This user does not have permission to be granted an access token");
    }

    public static function accessDenied() : self
    {
        return new self("This area is restricted.");
    }
}
