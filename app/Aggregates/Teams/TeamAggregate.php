<?php

namespace App\Aggregates\Teams;

use App\Exceptions\Teams\TeamException;
use App\StorableEvents\Teams\AddProjectToTeam;
use App\StorableEvents\Teams\TeamCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TeamAggregate extends AggregateRoot
{
    public string|null $name = null;
    public array $projects = [];
    public array $members = [];

    public function applyTeamCreated(TeamCreated $event)
    {
        $this->name = $event->name;
    }

    public function applyAddProjectToTeam(AddProjectToTeam $event)
    {
        $this->projects[$event->project_id] = [];
    }

    public function createTeam(string $name) : self
    {
        if(!is_null($this->name))
        {
            throw TeamException::teamAlreadyExists($name);
        }

        $this->recordThat(new TeamCreated($this->uuid(), $name));

        return $this;
    }

    public function addProjectToTeam(string $project_id) : self
    {
        if(array_key_exists($project_id, $this->projects))
        {
            throw TeamException::projectAlreadyAssigned()    ;
        }

        $this->recordThat(new AddProjectToTeam($this->uuid(), $project_id));

        return $this;
    }

    public function addUserToTeam() : self
    {
        return $this;
    }

    public function isProjectAssigndToTeam(string $project_id) : bool
    {
        return array_key_exists($project_id, $this->projects);
    }
}
