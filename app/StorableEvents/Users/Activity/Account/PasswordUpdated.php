<?php

namespace App\StorableEvents\Users\Activity\Account;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PasswordUpdated extends ShouldBeStored
{
    public function __construct(public string $user_id, protected string $pw)
    {
    }

    public function getPw()
    {
        return $this->pw;
    }
}
