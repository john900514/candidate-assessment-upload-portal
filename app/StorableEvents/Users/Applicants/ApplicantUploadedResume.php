<?php

namespace App\StorableEvents\Users\Applicants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ApplicantUploadedResume extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $path)
    {
    }
}
