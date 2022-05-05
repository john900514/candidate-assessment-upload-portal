<?php

namespace App\StorableEvents\Candidates\Assessments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SourceCodeSubmittedForAssessment extends ShouldBeStored
{
    public function __construct(public string $user_id,
                                public string $file_id,
                                public string $path,
                                public string $date,
                                public string $assessment_id)
    {
    }
}
