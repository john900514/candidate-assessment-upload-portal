<?php

namespace App\Reactors\Candidates;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Communication\MailingListAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Mail\Users\Employees\JobApplications\NoticeOfCandidateSubmittingJobApplication;
use App\Models\Communication\MailingList;
use App\StorableEvents\Candidates\JobApplications\UserSubmittedJobApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class JobApplicationReactor extends Reactor implements ShouldQueue
{
    public function onUserSubmittedJobApplication(UserSubmittedJobApplication $event)
    {
        // Get the Job Aggregate - get the job type
        $job_aggy = JobPositionAggregate::retrieve($event->job_id);

        // Query the DB for the UUID of the mailing list of that job position
        if(count($mailing_lists = $job_aggy->getAssociatedMailingLists()) > 0)
        {
            // @todo - filter out emailing those who want texts and text them
            // @todo - pusher implementation

            // Logic for those receiving emails
            foreach($mailing_lists as $idx => $mailing_list)
            {
                $list_aggy = MailingListAggregate::retrieve($mailing_list['id']);

                $mail_class = NoticeOfCandidateSubmittingJobApplication::class;
                $payload = [
                    'candidate' => $event->user_id,
                    'job' => $event->job_id
                ];
                // Get those users in an array by email
                foreach($list_aggy->getUserList() as $user_id => $email)
                {
                    // Fire off the emails with the aggregate to log the activity
                    UserAggregate::retrieve($user_id)
                        ->fireEmailToThisUser($mail_class, $payload)
                        ->persist();
                }
            }
        }
    }
}
