<?php

namespace App\StorableEvents\Candidates\Assessments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CandidateAssessmentTaskStatusUpdated extends ShouldBeStored
{
    public function __construct(public string $user_id,
                                public string $job_id,
                                public string $assessment_id,
                                public string $task_name,
                                public array $details)
    {
    }
}
