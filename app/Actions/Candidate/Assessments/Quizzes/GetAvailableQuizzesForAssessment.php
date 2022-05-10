<?php

namespace App\Actions\Candidate\Assessments\Quizzes;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Models\Candidates\Tests\Quiz;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAvailableQuizzesForAssessment
{
    use AsAction;

    public function rules() : array
    {
        return [
            'assessment_id' => ['bail', 'required', 'exists:assessments,id'],
        ];
    }

    public function handle(string $assessment_id) : array
    {
        $results = [];

        // Get the assessment aggy and an array of its assigned quizzes
        $assy_aggy = AssessmentAggregate::retrieve($assessment_id);
        $quiz_list = $assy_aggy->getQuizzes();

        // Get the assessment's concentration
        $concentration = $assy_aggy->getConcentration();

        $exempt_uuids = [];
        if(count($quiz_list) > 0)
        {
            $exempt_uuids = array_keys($quiz_list);
        }

        // Query the DB for quizzes in that concentration that are not in the quiz list
        if(count($exempt_uuids) > 0)
        {
            $available_quizzes = Quiz::whereConcentration($concentration)
                ->whereNotIn('id', $exempt_uuids)->whereActive(1)
                ->get();
        }
        else
        {
            $available_quizzes = Quiz::whereConcentration($concentration)
                ->whereActive(1)->get();
        }

        if(count($available_quizzes) > 0)
        {
            // if results, return them.
            $results = $available_quizzes->toArray();
        }

        return $results;
    }

    public function asController() :   array
    {
        $data = request()->all();
        return $this->handle($data['assessment_id']);
    }

    public function jsonResponse(array $result)
    {
        $results = ['success' => false];
        $code = 500;

        if($result)
        {
            $results = ['success' => true, 'options' => $result];
            $code = 200;
        }

        return response($results, $code);
    }
}
