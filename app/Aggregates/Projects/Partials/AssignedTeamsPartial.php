<?php

namespace App\Aggregates\Projects\Partials;

use App\Exceptions\Projects\ProjectException;
use App\StorableEvents\Projects\AddTeamToProject;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AssignedTeamsPartial extends AggregatePartial
{
    protected array $teams = [];

    public function applyAddTeamToProject(AddTeamToProject $event)
    {
        $this->teams[$event->team_id] = [];
    }

    public function addTeamToProject(string $team_id) : self
    {
        if(array_key_exists($team_id, $this->teams))
        {
            throw ProjectException::teamAlreadyAssigned();
        }

        $this->recordThat(new AddTeamToProject($this->aggregateRootUuid(), $team_id));

        return $this;
    }

    public function isTeamAssignedToProject(string $team_id) : bool
    {
        return array_key_exists($team_id, $this->teams);
    }
}
