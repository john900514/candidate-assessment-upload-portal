<?php

namespace App\StorableEvents\Candidates\Assessments\Tasks;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TaskDeactivated extends ShouldBeStored
{
    public function __construct(public string $assessment_id, public string $name)
    {
    }
}
