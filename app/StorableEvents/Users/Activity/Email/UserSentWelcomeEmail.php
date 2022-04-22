<?php

namespace App\StorableEvents\Users\Activity\Email;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserSentWelcomeEmail extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $employee_status)
    {
    }
}
