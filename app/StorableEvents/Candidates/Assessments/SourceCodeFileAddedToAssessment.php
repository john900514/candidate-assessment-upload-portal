<?php

namespace App\StorableEvents\Candidates\Assessments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SourceCodeFileAddedToAssessment extends ShouldBeStored
{
    public function __construct(public string $assessment_id, public string $source_id)
    {
    }
}
