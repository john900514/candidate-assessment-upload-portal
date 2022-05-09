<?php

namespace App\StorableEvents\Assets\Tests\Questions;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizQuestionDeactivated extends ShouldBeStored
{
    public function __construct(public string $quiz_id, public string $question_id)
    {
    }
}
