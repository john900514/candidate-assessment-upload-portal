<?php

namespace App\Projectors\Candidates;

use App\Aggregates\Users\UserAggregate;
use App\Models\UserDetails;
use App\StorableEvents\Candidates\JobApplications\UserSubmittedJobApplication;
use App\StorableEvents\Users\Applicants\ApplicantSubmittedJobApplication;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class JobApplicationProjector extends Projector
{
    public function onUserSubmittedJobApplication(UserSubmittedJobApplication $event)
    {
        UserAggregate::retrieve($event->user_id)
            ->logJobApplication($event->job_id)
            ->persist();
    }

    public function onApplicantSubmittedJobApplication(ApplicantSubmittedJobApplication $event)
    {
        $detail = UserDetails::create([
            'user_id' => $event->user_id,
            'name' => 'job_application',
            'value' => $event->job_id
        ]);
        $detail->misc = ['date' => $event->date];
        $detail->save();
    }
}
