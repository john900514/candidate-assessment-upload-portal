<?php

namespace App\StorableEvents\Users\Applicants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ApplicantSubmittedJobApplication extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $job_id, public string $date)
    {
    }
}
