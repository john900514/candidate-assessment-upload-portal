<?php

namespace App\Projectors\Assets;

use App\Models\Candidates\Tests\Quiz;
use App\StorableEvents\Assets\Tests\QuizCreated;
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
}
