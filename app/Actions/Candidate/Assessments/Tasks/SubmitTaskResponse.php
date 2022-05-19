<?php

namespace App\Actions\Candidate\Assessments\Tasks;

use App\Aggregates\Users\UserAggregate;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SubmitTaskResponse
{
    use AsAction;

    public function rules() : array
    {
        return [
            'assessment_id' => ['bail','required', 'exists:assessments,id'],
            'task_name' => ['bail','required','string', 'exists:assessment_tasks,task_name'],
            'status' => ['bail','required','string'],
            'explanation' => ['bail','required','string'],
        ];
    }

    public function handle(string $assessment_id, string $task_name, string $status, string $response) : array|false
    {
        $results = false;

        $user_aggy = UserAggregate::retrieve(backpack_user()->id);

        $jobs = $user_aggy->getOpenJobPositions();
        $job_ids = [];
        foreach ($jobs as $job_id)
        {
            if($assessment_status = $user_aggy->getAssessmentStatus($job_id, $assessment_id))
            {
                $job_ids[$job_id] = $assessment_status;
            }
        }

        if(count($job_ids) > 0)
        {
            foreach($job_ids as $job_id => $job_status)
            {
                // If the assessment has not started, set the Assessment to started.
                if($job_status['status'] == 'Not Started')
                {
                    $user_aggy = $user_aggy->updateJobAssessmentStatus($job_id, $assessment_id, 'Started');
                }

                $user_aggy = $user_aggy->updateAssessmentTaskStatus($job_id, $assessment_id, $task_name, $status, $response);
            }

            // Persist the aggregate
            $user_aggy->persist();

            // Re-init the aggy and prepare the response similar to the page load
            $user_aggy = UserAggregate::retrieve(backpack_user()->id);
            $open_positions = $user_aggy->getOpenJobPositions();
            foreach($open_positions as $idx => $job_id)
            {
                if($assessment_status = $user_aggy->getAssessmentStatus($job_id, $assessment_id))
                {
                    $assessment_status['time_left'] = '4:00';
                    if(array_key_exists('time_expires', $assessment_status))
                    {
                        $now = Carbon::parse(date('Y-m-d H:i:s'));
                        $expy = Carbon::parse(date($assessment_status['time_expires']));
                        $min_left = $now->diff($expy)->i > 9 ? $now->diff($expy)->i : '0'.$now->diff($expy)->i;
                        $assessment_status['time_left'] = $now->diff($expy)->h.":".$min_left;
                    }

                    $results = [
                        'userData' => $assessment_status ?? []
                    ];
                    // check if the installer was downloaded and we'll assume it was installed
                    $results['userData']['sourceInstalled'] = $user_aggy->hasDownloadedInstaller();
                    break;
                }
            }

        }

        return $results;
    }

    public function asController() : array|false
    {
        $data = request()->all();
        return $this->handle($data['assessment_id'], $data['task_name'], $data['status'], $data['explanation']);
    }

    public function jsonResponse(array|false $result)
    {
        $results = ['success' => false];
        $code = 500;

        if($result)
        {
            $results = $result;

            $code = 200;
        }

        return response($results, $code);
    }
}
