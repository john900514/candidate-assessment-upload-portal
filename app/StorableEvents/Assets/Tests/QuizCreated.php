<?php

namespace App\StorableEvents\Assets\Tests;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class QuizCreated extends ShouldBeStored
{
    public function __construct(public string $quiz_id,
                                public string $name,
                                public string $concentration)
    {

    }
}
