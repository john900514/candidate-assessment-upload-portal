<?php

namespace App\Projectors\Candidates;

use App\Models\Candidates\JobPosition;
use App\StorableEvents\Candidates\JobPositionCreated;
use App\StorableEvents\Candidates\QualifiedRoleAdded;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CandidateProjector extends Projector
{
    public function onJobPositionCreated(JobPositionCreated $event)
    {
        $job = JobPosition::create([
            'job_title' => $event->config['position'],
            'concentration' => $event->config['concentration'],
            'awarded_role' => $event->config['awarded_role'],
            'active' => 0
        ]);

        $job->id = $event->job_id;
        $job->save();
    }

    public function onQualifiedRoleAdded(QualifiedRoleAdded $event)
    {

    }

}
