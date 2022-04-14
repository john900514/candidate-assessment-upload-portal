<?php

namespace App\Aggregates\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\Partials\QuizQuestionPartial;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class QuizAggregate extends AggregateRoot
{
    protected QuizQuestionPartial $quiz_questions;

    public function __construct()
    {
        $this->quiz_questions = new QuizQuestionPartial($this);
    }
}
