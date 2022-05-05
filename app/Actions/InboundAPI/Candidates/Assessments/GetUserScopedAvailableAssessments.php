<?php

namespace App\Actions\InboundAPI\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserScopedAvailableAssessments
{
    use AsAction;

    public function handle(string $user_id) : array | false | string
    {
        $results = false;
        $aggy = UserAggregate::retrieve($user_id);

        // Check if the user is an applicant or fail with string
        if($aggy->isApplicant())
        {
            // Check if the applicant is qualified or fail with string
            if($aggy->getCandidateStatus() == 'qualified-candidate')
            {
                // Get Open Job Positions or fail with string
                $jobs = $aggy->getOpenJobPositions();
                if(count($jobs) > 0)
                {
                    // Get array of assessments for each or fail with string
                    $assessments = [];
                    foreach ($jobs as $idx => $job_id)
                    {
                        $job_aggy = JobPositionAggregate::retrieve($job_id);

                        $job_tests = $job_aggy->getAssessments();

                        if(count($job_tests) > 0)
                        {
                            foreach ($job_tests as $assessment_id)
                            {
                                if(!array_key_exists($assessment_id, $assessments))
                                {
                                    $user_ass_status = $aggy->getAssessmentStatus($job_id, $assessment_id);
                                    if(($user_ass_status['status'] != 'Not Started') && ($user_ass_status['status'] != 'Completed'))
                                    {
                                        $assessments[$assessment_id] = $assessment_id;
                                    }
                                }
                            }
                        }
                    }

                    if(count($assessments) > 0)
                    {
                        $results = [];
                        foreach ($assessments as $idx => $assessment)
                        {
                            $ass_aggy = AssessmentAggregate::retrieve($assessment);
                            $results[$assessment] = $ass_aggy->getName();
                        }
                    }

                }
                else
                {
                    $results = 'A Job with a Source Code Assessment must be available for you to apply for.';
                }

            }
            else
            {
                $results = 'Access is for Qualified Candidates.';
            }
        }
        else
        {
            $results = 'Access is for Candidates.';
        }

        return $results;
    }

    public function asController()
    {
        backpack_auth()->login(auth()->user());
        if(!is_null($user = backpack_user()))
        {
            return $this->handle($user->id);
        }
        else
        {
            return 'Invalid Credentials or you Need you login from the API.';
        }

    }

    public function jsonResponse(string|false|array $result)
    {
        $results = false;
        $code = 500;

        if(is_string($result))
        {
            $results = ['error' => $result];
            $code = 401;
        }
        elseif(is_array($result))
        {
            $results = $result;
            $code = 200;
        }

        return response($results, $code);
    }
}
