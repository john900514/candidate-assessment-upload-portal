<?php

namespace App\Aggregates\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\Partials\QuizQuestionPartial;
use App\Exceptions\Assets\QuizException;
use App\StorableEvents\Assets\Tests\QuizActivated;
use App\StorableEvents\Assets\Tests\QuizCreated;
use App\StorableEvents\Assets\Tests\QuizDeactivated;
use App\StorableEvents\Assets\Tests\QuizUpdated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class QuizAggregate extends AggregateRoot
{
    protected bool $active = false;
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

    public function applyQuizUpdated(QuizUpdated $event)
    {
        $col = $event->column;
        $this->$col = $event->value;
    }

    public function applyQuizActivated(QuizActivated $event)
    {
        $this->active = true;
    }

    public function applyQuizDeactivated(QuizDeactivated $event)
    {
        $this->active = false;
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

    public function updateQuizName(string $name) : self
    {
        $this->recordThat(new QuizUpdated($this->uuid(), 'name', $name));
        return $this;
    }

    public function activateQuiz() : self
    {
        if($this->active)
        {
            throw QuizException::quizAlreadyActivated();
        }

        $this->recordThat(new QuizActivated($this->uuid(), date('Y-m-d H:i:s')));

        return $this;
    }

    public function deactivateQuiz() : self
    {
        if(!$this->active)
        {
            throw QuizException::quizAlreadyDeactivated();
        }

        $this->recordThat(new QuizDeactivated($this->uuid(), date('Y-m-d H:i:s')));

        return $this;
    }

    public function updateConcentration(string $concentration) : self
    {
        $this->recordThat(new QuizUpdated($this->uuid(), 'concentration', $concentration));

        return $this;
    }

    public function addNewQuestion(string $question_id, array $details) : self
    {
        $this->quiz_questions->addNewQuestion($question_id, $details);
        return $this;
    }

    public function deactivateQuestion(string $question_id) : self
    {
        $this->quiz_questions->deactivateQuestion($question_id);
        return $this;
    }

    public function getQuestions() : array
    {
        return $this->quiz_questions->getQuestions();
    }

    public function getName() : string|null
    {
        return $this->name;
    }

    public function getConcentration() : string|null
    {
        return $this->concentration;
    }

    public function isActive() : bool
    {
        return $this->active;
    }
}
