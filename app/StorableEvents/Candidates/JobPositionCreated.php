<?php

namespace App\StorableEvents\Candidates;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class JobPositionCreated extends ShouldBeStored
{
    public function __construct(public string $job_id, public array $config)
    {
    }
}
