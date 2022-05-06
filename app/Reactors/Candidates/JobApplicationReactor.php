<?php

namespace App\Reactors\Candidates;

use App\StorableEvents\Candidates\JobApplications\UserSubmittedJobApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class JobApplicationReactor extends Reactor implements ShouldQueue
{
    public function onUserSubmittedJobApplication(UserSubmittedJobApplication $event)
    {
        /**
         * STEPS
         * 1. Make a mailing list DB table, migration, model and aggregate
         * 2. Make a seeder that makes new mailing lists and adds users to them
         * 3. Get the Job Aggregate - get the job type
         * 4. Query the DB for the UUID of the mailing list of that job position
         * 5. Get those users in an array by email
         * 6. Make a mail blade
         * 7. Make a mail class
         * 8. Fire off the emails
         */
        // @todo - filter out emailing those who want texts and text them
        // @todo - push implementation
    }
}
