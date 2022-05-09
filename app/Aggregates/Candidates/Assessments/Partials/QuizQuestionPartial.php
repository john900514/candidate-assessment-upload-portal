<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use App\Exceptions\Assets\QuizException;
use App\StorableEvents\Assets\Tests\Questions\QuizQuestionCreated;
use App\StorableEvents\Assets\Tests\Questions\QuizQuestionDeactivated;
use App\StorableEvents\Assets\Tests\Questions\QuizQuestionReactivated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class QuizQuestionPartial extends AggregatePartial
{
    protected array $questions = [];
    protected array $deactivated_questions = [];

    public function applyQuizQuestionCreated(QuizQuestionCreated $event)
    {
        $this->questions[$event->question_id] = $event->details;
    }

    public function applyQuizQuestionDeactivated(QuizQuestionDeactivated $event)
    {
        $this->deactivated_questions[$event->question_id] = $this->questions[$event->question_id];
        unset($this->questions[$event->question_id]);
    }

    public function applyQuizQuestionReactivated(QuizQuestionReactivated $event)
    {
        $this->questions[$event->question_id] = $this->deactivated_questions[$event->question_id];
        unset($this->deactivated_questions[$event->question_id]);
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

    public function reactivateQuestion(string $question_id) : self
    {
        if(array_key_exists($question_id, $this->deactivated_questions))
        {
            $this->recordThat(new QuizQuestionReactivated($this->aggregateRootUuid(), $question_id));
        }
        return $this;
    }

    public function deactivateQuestion(string $question_id) : self
    {
        if(array_key_exists($question_id, $this->questions))
        {
            $this->recordThat(new QuizQuestionDeactivated($this->aggregateRootUuid(), $question_id));
        }
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
