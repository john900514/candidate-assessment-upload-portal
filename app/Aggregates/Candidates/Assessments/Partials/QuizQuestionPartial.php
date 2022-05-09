<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use App\Exceptions\Assets\QuizException;
use App\StorableEvents\Assets\Tests\Questions\QuizQuestionCreated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class QuizQuestionPartial extends AggregatePartial
{
    protected array $questions = [];
    protected array $deactivated_questions = [];

    public function applyQuizQuestionCreated(QuizQuestionCreated $event)
    {
        $this->questions[$event->question_id] = $event->details;
    }

    public function addNewQuestion(string $question_id, array $details) : self
    {
        if($this->getQuestion($question_id))
        {
            throw QuizException::questionAlreadyAdded();
        }

        $this->recordThat(new QuizQuestionCreated($question_id, $details, $this->aggregateRootUuid()));

        return $this;
    }

    public function getQuestions() : array
    {
        return $this->questions;
    }

    public function getQuestion(string $uuid) : array | false
    {
        return $this->questions[$uuid] ?? false;
    }
}
