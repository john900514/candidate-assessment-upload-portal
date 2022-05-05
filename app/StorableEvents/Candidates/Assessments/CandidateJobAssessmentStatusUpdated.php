<?php

namespace App\StorableEvents\Candidates\Assessments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CandidateJobAssessmentStatusUpdated extends ShouldBeStored
{
    public function __construct(public string $user_id,
                                public string $job_id,
                                public string $assessment_id,
                                public string $new_status,
                                public array $details = [])
    {

    }
}
