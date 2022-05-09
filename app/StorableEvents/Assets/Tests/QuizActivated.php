<?php

namespace App\StorableEvents\Assets\Tests;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizActivated extends ShouldBeStored
{
    public function __construct(public string $quiz_id, public string $date)
    {
    }
}
