<?php

namespace App\StorableEvents\Assets\Tests;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizUpdated extends ShouldBeStored
{
    public function __construct(public string $quiz_id, public string $column, public string $value)
    {
    }
}
