<?php

namespace App\Actions\Candidate\Assessments\Tasks;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class ReactivateTask
{
    use AsAction;

    public function rules() : array
    {
        return [
            'assessment_id' => ['bail','required', 'exists:assessments,id'],
            'task_name' => ['bail','required','string'],
            'task_description' => ['bail','required','string'],
            'required' => ['bail','required','bool']
        ];
    }

    public function handle() : string|false
    {
        $data = request()->all();
        try {
            AssessmentAggregate::retrieve($data['assessment_id'])
                ->addNewTask($data)->persist();

            $results = true;
        }
        catch(\Exception $e)
        {
            $results = false;
        }


        return $results;
    }

    public function asController() : string|false
    {
        return $this->handle();
    }

    public function jsonResponse(string|false $result)
    {
        $results = ['success' => false];
        $code = 500;

        if($result)
        {
            $results = ['success' => true];
            $code = 200;
        }

        return response($results, $code);
    }
}
