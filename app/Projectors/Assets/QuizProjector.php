<?php

namespace App\Projectors\Assets;

use App\Models\Candidates\Tests\Question;
use App\Models\Candidates\Tests\Quiz;
use App\StorableEvents\Assets\Tests\Questions\QuizQuestionCreated;
use App\StorableEvents\Assets\Tests\QuizActivated;
use App\StorableEvents\Assets\Tests\QuizCreated;
use App\StorableEvents\Assets\Tests\QuizDeactivated;
use App\StorableEvents\Assets\Tests\QuizUpdated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class QuizProjector extends Projector
{
    public function onQuizCreated(QuizCreated $event)
    {
        $record = Quiz::create([
            'name' => $event->name,
            'concentration' => $event->concentration,
        ]);
        $record->update(['id' => $event->quiz_id]);
    }

    public function onQuizUpdated(QuizUpdated $event)
    {
        $record = Quiz::find($event->quiz_id);
        $record->update([$event->column => $event->value]);
    }

    public function onQuizActivated(QuizActivated $event)
    {
        $record = Quiz::find($event->quiz_id);
        $record->update(['active' => 1]);
    }

    public function onQuizDeactivated(QuizDeactivated $event)
    {
        $record = Quiz::find($event->quiz_id);
        $record->update(['active' => 0]);
    }

    public function onQuizQuestionCreated(QuizQuestionCreated $event)
    {
        $question = Question::create([
            'quiz_id' => $event->quiz_id,
            'question_name' => $event->details['question_name'],
            'question_type' => $event->details['question_type']
        ]);

        $question->update(['id' => $event->question_id]);

        if(array_key_exists('answer', $event->details))
        {
            $question->update(['answer' => $event->details['answer']]);
        }

        if(array_key_exists('choices', $event->details) && ($event->details['question_type']) == 'MULTIPLE_CHOICE')
        {
            $question->update(['available_choices' => $event->details['choices']]);
        }
    }

}
