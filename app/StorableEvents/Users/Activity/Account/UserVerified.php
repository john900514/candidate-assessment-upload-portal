<?php

namespace App\StorableEvents\Users\Activity\Account;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserVerified extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $date)
    {
    }
}
