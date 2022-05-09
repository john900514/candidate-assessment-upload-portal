<?php

namespace App\Aggregates\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\Partials\QuizQuestionPartial;
use App\Exceptions\Assets\QuizException;
use App\StorableEvents\Assets\Tests\QuizCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class QuizAggregate extends AggregateRoot
{
    protected string|null $name = null;
    protected string|null $concentration = null;

    protected QuizQuestionPartial $quiz_questions;

    public function __construct()
    {
        $this->quiz_questions = new QuizQuestionPartial($this);
    }

    public function applyQuizCreated(QuizCreated $event)
    {
        $this->name = $event->name;
        $this->concentration = $event->concentration;
    }

    public function createQuiz(string $name, string $concentration) : self
    {

        if(!is_null($this->name))
        {
            throw QuizException::quizAlreadyCreated($name);
        }

        $this->recordThat(new QuizCreated($this->uuid(), $name, $concentration));
        return $this;
    }

    public function getQuestions() : array
    {
        return $this->quiz_questions->getQuestions();
    }
}
