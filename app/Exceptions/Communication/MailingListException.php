<?php

namespace App\Exceptions\Communication;

use DomainException;

class MailingListException extends DomainException
{
    public static function mailingListAlreadyCreated()
    {
        return new self('Mailing List Already Created');
    }
}
