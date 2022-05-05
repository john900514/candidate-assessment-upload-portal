<?php

namespace App\Projectors\Candidates;

use App\Models\Assets\UploadedFile;
use App\Models\Assets\UserFileUpload;
use App\Models\Candidates\JobPosition;
use App\Models\User;
use App\Models\UserDetails;
use App\StorableEvents\Candidates\Assessments\CandidateJobAssessmentStatusUpdated;
use App\StorableEvents\Candidates\Assessments\SourceCodeSubmittedForAssessment;
use App\StorableEvents\Candidates\AssessmentsAddedOrUpdatedToJobPosition;
use App\StorableEvents\Candidates\JobDescription;
use App\StorableEvents\Candidates\JobPositionCreated;
use App\StorableEvents\Candidates\JobPositionUpdated;
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
        // @todo - do stuff related to this
    }

    public function onJobPositionUpdated(JobPositionUpdated $event)
    {
        $job = JobPosition::find($event->job_id);
        $job->update([
            'job_title' => $event->config['position'],
            'concentration' => $event->config['concentration']['value'],
            'awarded_role' => $event->config['awarded_role']['value'],
            'active' => $event->config['active']
        ]);
    }

    public function onCandidateJobAssessmentStatusUpdated(CandidateJobAssessmentStatusUpdated $event)
    {
        $misc = $event->details;
        $misc['status'] = $event->new_status;
        $misc['job_id'] = $event->job_id;

        UserDetails::firstOrCreate([
            'user_id' => $event->user_id,
            'name' => 'assessment_status',
            'value' => $event->assessment_id,
            'misc' => $misc,
            'active' => 1
        ]);
    }

    public function onSourceCodeSubmittedForAssessment(SourceCodeSubmittedForAssessment $event)
    {
        $file_record = UploadedFile::firstOrCreate([
            'file_path' => "{$event->path}{$event->assessment_id}.zip",
            'entity_id' => $event->user_id,
            'entity' => User::class,
        ]);

        if($file_record->id != $event->file_id)
        {
            $file_record->update(['id' => $event->file_id]);
        }

        UserFileUpload::create([
           'user_id' => $event->user_id,
            'file_id' => $event->file_id,
            'file_nickname' => "Assessment {$event->assessment_id}",
            'description' => 'User submitted source code for assessment on '.$event->date
        ]);
    }
}
