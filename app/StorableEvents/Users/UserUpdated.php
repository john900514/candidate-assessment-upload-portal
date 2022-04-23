<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserUpdated extends ShouldBeStored
{
    public function __construct(public string $user_id, public array $details)
    {
    }
}
