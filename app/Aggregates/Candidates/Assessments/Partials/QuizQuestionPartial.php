<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class QuizQuestionPartial extends AggregatePartial
{
    protected array $questions = [];

    public function getQuestions() : array
    {
        return $this->questions;
    }
}
