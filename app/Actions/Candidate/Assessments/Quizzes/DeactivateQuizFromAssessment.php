<?php

namespace App\Actions\Candidate\Assessments\Quizzes;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class DeactivateQuizFromAssessment
{
    use AsAction;

    public function rules() : array
    {
        return [
            'assessment_id' => ['bail','required', 'exists:assessments,id'],
            'quiz_id' => ['bail','required', 'exists:quizzes,id'],
        ];
    }

    public function handle(string $assessment_id, string $quiz_id) : string|false
    {
        try {
            AssessmentAggregate::retrieve($assessment_id)
                ->unlinkQuizFromAssessment($quiz_id)->persist();

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
        $data = request()->all();
        return $this->handle($data['assessment_id'], $data['quiz_id']);
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
