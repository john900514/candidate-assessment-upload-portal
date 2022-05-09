<?php

namespace App\StorableEvents\Assets\Tests\Questions;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizQuestionCreated extends ShouldBeStored
{
    public function __construct(public string $question_id, public array $details, public string $quiz_id)
    {
    }
}
