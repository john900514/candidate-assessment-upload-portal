<?php

namespace App\StorableEvents\Candidates\JobApplications;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UsersJobApplicationWasReverted extends ShouldBeStored
{
    public function __construct(public string $job_id, public string $user_id)
    {
    }
}
