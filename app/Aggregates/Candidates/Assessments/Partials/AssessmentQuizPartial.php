<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use App\Exceptions\Candidates\AssessmentException;
use App\StorableEvents\Candidates\Assessments\Quizzes\QuizLinkedToAssessment;
use App\StorableEvents\Candidates\Assessments\Quizzes\QuizUnlinkedFromAssessment;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AssessmentQuizPartial extends AggregatePartial
{
    protected array $quizzes = [];

    public function applyQuizLinkedToAssessment(QuizLinkedToAssessment $event)
    {
        $this->quizzes[$event->quiz_id] = $event->quiz_id;
    }

    public function applyQuizUnlinkedFromAssessment(QuizUnlinkedFromAssessment $event)
    {
        unset($this->quizzes[$event->quiz_id]);
    }

    public function linkQuizToAssessment(string $quiz_id) : self
    {
        if(array_key_exists($quiz_id, $this->quizzes))
        {
            throw AssessmentException::quizAlreadyLinked();
        }

        $this->recordThat(new QuizLinkedToAssessment($this->aggregateRootUuid(), $quiz_id));

        return $this;
    }

    public function unlinkQuizFromAssessment(string $quiz_id) : self
    {
        if(!array_key_exists($quiz_id, $this->quizzes))
        {
            throw AssessmentException::quizNotLinked();
        }

        $this->recordThat(new QuizUnlinkedFromAssessment($this->aggregateRootUuid(), $quiz_id));

        return $this;
    }

    public function getQuizzes() : array
    {
        return $this->quizzes;
    }

    public function getDetailedQuizzes() : array
    {
        $results = [];

        foreach($this->quizzes as $quiz_id)
        {
            $quiz_record = Quiz::find($quiz_id);
            if(!is_null($quiz_record))
            {
                $results[] = $quiz_record->toArray();
            }
        }
        return $results;
    }
}
