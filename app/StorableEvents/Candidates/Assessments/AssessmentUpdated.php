<?php

namespace App\StorableEvents\Candidates\Assessments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AssessmentUpdated extends ShouldBeStored
{
    public function __construct(public string $assessment_id,
                                public array $payload,
                                public bool $has_quizzes,
                                public bool $has_source_code,
                                public string $updated_by_user_id)
    {
    }
}
