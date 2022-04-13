<?php

namespace App\StorableEvents\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamCreated extends ShouldBeStored
{
    public function __construct(public string $team_id, public string $name)
    {
    }
}
