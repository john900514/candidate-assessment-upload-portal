<?php

namespace App\Exceptions\Communication;

use DomainException;

class MailingListException extends DomainException
{
    public static function mailingListAlreadyCreated()
    {
        return new self('Mailing List Already Created');
    }

    public static function userAlreadyInMailingList(string $email)
    {
        return new self("$email is already in this mailing list.");
    }

    public static function userNotInMailingList(string $email)
    {
        return new self("$email is NOT in this mailing list.");
    }
}
