<?php

namespace App\StorableEvents\Candidates\Assessments\Quizzes;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizLinkedToAssessment extends ShouldBeStored
{
    public function __construct(public string $assessment_id, public string $quiz_id)
    {
    }
}
