<?php

namespace App\Projectors\Projects;

use App\Aggregates\Teams\TeamAggregate;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectTeam;
use App\StorableEvents\Projects\ProjectCreated;
use App\StorableEvents\Projects\AddTeamToProject;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProjectsProjector extends Projector
{
    public function onProjectCreated(ProjectCreated $event)
    {
        $project = Project::create([
            'client_id' => $event->client_id,
            'project_name' => $event->name
        ]);
        $project->id = $event->project_id;
        $project->save();
    }

    public function onAddTeamToProject(AddTeamToProject $event)
    {
        ProjectTeam::create([
            'project_id' => $event->project_id,
            'team_id' => $event->team_id
        ]);

        $team_aggy = TeamAggregate::retrieve($event->team_id);

        if(!$team_aggy->isProjectAssigndToTeam($event->project_id))
        {
            $team_aggy->addProjectToTeam($event->project_id)->persist();
        }
    }
}
