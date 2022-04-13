<?php

namespace App\StorableEvents\Projects;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AddTeamToProject extends ShouldBeStored
{
    public function __construct(public string $project_id, public string $team_id)
    {
    }
}
