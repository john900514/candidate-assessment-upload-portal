<?php

namespace App\Actions\Candidate\ToDos;

use App\Actions\InboundAPI\Assets\Files\SourceCode\GetUserScopedAvailableSourceCodes;
use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class GetToDoList
{
    use AsAction;

    public function handle(string $user_id)
    {
        $results = [];

        $aggy = UserAggregate::retrieve($user_id);

        if(!$aggy->getAccessToken())
        {
            $results[] = 'Generate your <a href="/portal/edit-account-info" class="link" target="_blank">access token</a>!';
        }

        if(!$aggy->hasDownloadedInstaller())
        {
            $results[] = 'Download the CLI Tool and login to get source code for your assessment(s)!';
        }
        else
        {
            $sources = GetUserScopedAvailableSourceCodes::run($user_id);
            if(is_array($sources) && (count($sources) > 0))
            {
                foreach ($sources as $source)
                {
                    $results[] = 'Source Code Available from the CLI Tool - <b>'.$source['file_name'].'</b>';
                }
            }


            $jobs = $aggy->getOpenJobPositions();
            if(count($jobs) > 0)
            {
                foreach ($jobs as $job_id)
                {
                    // For each job, get all assessments.
                    $job_status = $aggy->getAssessmentStatus($job_id);
                    // Get all open jobs for the applicant that are < Applied
                    if($job_status['status'] != 'Applied')
                    {
                        $no_assessments_reqd = count($job_status['assessments']);
                        $todo_assessments = [];
                        $done_assessments = [];
                        // get all assessments that are > Not Started in status

                        foreach($job_status['assessments'] as $assessment_id => $user_status)
                        {
                            // If each assessment has code uploaded, 0 quizzes and 0 tasks left to complete, add it to list.
                            if($user_status['status'] == 'Completed')
                            {
                                $done_assessments[$assessment_id] = $user_status;
                            }
                            else
                            {
                                $todo_assessments[$assessment_id] = $user_status;
                            }
                        }

                        if(count($todo_assessments) > 0)
                        {
                            foreach ($todo_assessments as $assessment_id => $todo_user_status)
                            {
                                $ass_aggy = AssessmentAggregate::retrieve($assessment_id);
                                // If there is source code, and it hasn't been uploaded, add to the list to upload the code for that assessment
                                if($todo_user_status['sourceReqd'] && (!$todo_user_status['sourceUploaded']))
                                {
                                    $results[] = 'Use the Installer tool to upload source code for  - <b>'.$ass_aggy->getName().'</b>';
                                }


                                // @todo - If there are quizzes and any left to take, todo for that quiz for that assessment
                                if(!$todo_user_status['quizzesCompleted'])
                                {

                                }

                                // @todo - If there are tasks and any left to complete, todo for that task for that assessment
                                if(!$todo_user_status['tasksCompleted'])
                                {

                                }
                            }
                        }

                        // If the size of the done list == the amount of reqd assessments, then do to "APPLY FOR POSITION"
                        if(count($done_assessments) == $no_assessments_reqd)
                        {
                            $job_aggy = JobPositionAggregate::retrieve($job_id);
                            $results[] = 'Submit Your Job Application for  - <b>'.$job_aggy->getJobTitle().'</b>';
                        }
                    }
                }
            }
        }

        return $results;
    }
}
