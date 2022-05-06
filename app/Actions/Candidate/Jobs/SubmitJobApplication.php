<?php

namespace App\Actions\Candidate\Jobs;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Exceptions\Candidates\JobPositionException;
use Lorisleiva\Actions\Concerns\AsAction;

class SubmitJobApplication
{
    use AsAction;

    public function rules() : array
    {
        return [
            'job_id' => ['bail','required', 'exists:job_positions,id']
        ];
    }

    public function handle(string $user_id, string $job_id)
    {
        $results = false;

        $user_aggy = UserAggregate::retrieve($user_id);
        $application_results = $user_aggy->getAssessmentStatus($job_id);

        if($application_results && ($application_results['status'] == 'Ready to Apply'))
        {
            try {
                JobPositionAggregate::retrieve($job_id)
                    ->submitUserJobApplication($user_id, $user_aggy->getEmail())
                    ->persist();

                $results = ['success' => true];
            }
            catch(JobPositionException $e)
            {
                $results = $e->getMessage();
            }
        }
        else
        {
            $results = 'Job Application is not finished. Complete the requirements, then ';
        }

        return $results;
    }

    public function asController()
    {
        return $this->handle(backpack_user()->id, request()->get('job_id'));
    }

    public function jsonResponse(string|false|array $result)
    {
        $results = ['message' => 'Unknown Error.'];
        $code = 500;

        if(is_string($result))
        {
            $results = ['message' => $result];
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
