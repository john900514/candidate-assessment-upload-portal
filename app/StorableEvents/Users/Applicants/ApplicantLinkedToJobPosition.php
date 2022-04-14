<?php

namespace App\StorableEvents\Users\Applicants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ApplicantLinkedToJobPosition extends ShouldBeStored
{
    public function __construct(public string $user_id, public array $job_positions)
    {
    }
}
