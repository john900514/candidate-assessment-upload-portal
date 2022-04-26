<?php

namespace App\StorableEvents\Candidates\Assessments\Tasks;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewTaskCreated extends ShouldBeStored
{
    public function __construct(public string $assessment_id, public array $task_data)
    {
    }
}
