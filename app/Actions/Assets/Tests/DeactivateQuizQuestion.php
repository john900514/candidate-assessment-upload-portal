<?php

namespace App\Actions\Assets\Tests;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\Assessments\QuizAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class DeactivateQuizQuestion
{
    use AsAction;

    public function rules() : array
    {
        return [
            'quiz_id' => ['bail','required', 'exists:quizzes,id'],
            'question_id' => ['bail','required','exists:questions,id'],
        ];
    }

    public function handle() : string|false
    {
        $data = request()->all();
        try {
            QuizAggregate::retrieve($data['quiz_id'])
                ->deactivateQuestion($data['question_id'])->persist();

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
