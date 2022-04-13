<?php

namespace App\StorableEvents\Projects;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProjectCreated extends ShouldBeStored
{
    public function __construct(public string $project_id, public string $client_id, public string $name)
    {

    }
}
