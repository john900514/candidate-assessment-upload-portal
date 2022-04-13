<?php

namespace App\Projectors\Teams;

use App\Aggregates\Projects\ProjectAggregate;
use App\Models\Teams\Team;
use App\StorableEvents\Teams\AddProjectToTeam;
use App\StorableEvents\Teams\TeamCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TeamProjector extends Projector
{
    public function onTeamCreated(TeamCreated $event)
    {
        $team = Team::create(['team_name' => $event->name]);
        $team->id = $event->team_id;
        $team->save();
    }

    public function onAddProjectToTeam(AddProjectToTeam $event)
    {
        $project_aggy = ProjectAggregate::retrieve($event->project_id);
        if(!$project_aggy->isTeamAssignedToProject($event->team_id))
        {
            $project_aggy->addTeamToProject($event->team_id)->persist();
        }
    }
}
