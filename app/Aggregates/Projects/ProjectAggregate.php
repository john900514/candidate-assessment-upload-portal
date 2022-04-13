<?php

namespace App\Aggregates\Projects;

use App\Aggregates\Projects\Partials\AssignedTeamsPartial;
use App\Exceptions\Projects\ProjectException;
use App\StorableEvents\Projects\ProjectCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ProjectAggregate extends AggregateRoot
{
    protected string|null $name = null;
    protected string|null $client_id = null;

    protected AssignedTeamsPartial $assigned_teams;

    public function __construct()
    {
        $this->assigned_teams = new AssignedTeamsPartial($this);
    }

    public function applyProjectCreated(ProjectCreated $event)
    {
        $this->name = $event->name;
        $this->client_id = $event->client_id;
    }

    public function createProject(string $name, string $client_id) : self
    {
        if(!is_null($this->name))
        {
            throw ProjectException::projectAlreadyExists($name);
        }

        $this->recordThat(new ProjectCreated($this->uuid(), $client_id, $name));

        return $this;
    }

    public function addTeamToProject(string $team_id) : self
    {
        $this->assigned_teams->addTeamToProject($team_id);
        return $this;
    }

    public function isTeamAssignedToProject(string $team_id) : bool
    {
        return $this->assigned_teams->isTeamAssignedToProject($team_id);
    }
}
