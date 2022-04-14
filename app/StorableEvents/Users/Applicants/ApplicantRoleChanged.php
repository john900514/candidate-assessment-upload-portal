<?php

namespace App\StorableEvents\Users\Applicants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ApplicantRoleChanged extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $role)
    {
    }
}
