<?php

namespace App\Actions\Candidate\Users;

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
                    // @todo - evaluate if a user has finishing applying for a job
                    $results[] = [
                        'jobTitle' => $job_aggy->getJobTitle(),
                        'assessments' => count($job_aggy->getAssessments()),
                        'status' => 'Not Applied',
                        'statusBadge' => 'badge-danger'
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
