<?php

namespace App\Actions\Candidate\Users;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class FetchOpenJobs
{
    use AsAction;

    public function handle(string $user_id) : array | false
    {
        $results = false;

        $aggy = UserAggregate::retrieve($user_id);

        if($aggy->isApplicant())
        {
            $results = [];

            $open_ids = $aggy->getOpenJobPositions();
            if(count($open_ids) > 0)
            {
                foreach ($open_ids as $open_id)
                {
                    $job_aggy = JobPositionAggregate::retrieve($open_id);
                    $assessments = $job_aggy->getAssessments();
                    $assessment_data = [];

                    foreach($assessments as $assessment)
                    {
                        $ass_aggy = AssessmentAggregate::retrieve($assessment);
                        $status = 'Not Started';
                        $badge = 'badge-danger';
                        $assessment_data[] = [
                            'id' => $assessment,
                            'name' => $ass_aggy->getName(),
                            'status' => $status,
                            'badge' => $badge
                        ];
                    }
                    /**
                     * STEPS.
                     * 2. Get Assessment Names and IDs
                     * 3. Get Status on User's Progress for each assessment (right now not-started)
                     */
                    // @todo - evaluate if a user has finishing applying for a job
                    $results[] = [
                        'jobTitle' => $job_aggy->getJobTitle(),
                        'assessments' => count($assessments),
                        'status' => 'Not Applied',
                        'statusBadge' => 'badge-danger',
                        'desc' => $job_aggy->getDesc(),
                        'job_id' => $open_id,
                        'assessmentData' => $assessment_data
                    ];
                }
            }
        }


        return $results;
    }

    public function asController()
    {
        $user = backpack_user()->id ?? auth()->user()->id;

        if($user)
        {
            return $this->handle($user);
        }

        return false;
    }

    public function jsonResponse(array|false $result)
    {
        $results = false;
        $code = 500;

        if($result)
        {
            $results = $result;
            $code = 200;
        }

        return response($results, $code);
    }
}
