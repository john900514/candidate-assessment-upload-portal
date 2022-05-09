<?php

namespace App\Actions\Assets\Tests;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\Assessments\QuizAggregate;
use Lorisleiva\Actions\Concerns\AsAction;
use Ramsey\Uuid\Uuid;

class CreateNewQuizQuestion
{
    use AsAction;

    public function rules() : array
    {
        return [
            'quiz_id' => ['bail','required', 'exists:quizzes,id'],
            'question_name' => ['bail','required','string'],
            'question_type' => ['bail','required','string'],
            'answer' => ['sometimes','required','string'],
            'choices'=> ['sometimes','required','array'],
        ];
    }

    public function handle(string $quiz_id, array $payload) : string | false
    {
        try {
            QuizAggregate::retrieve($quiz_id)
                ->addNewQuestion(Uuid::uuid4()->toString(), $payload)
                ->persist();

            $results = true;
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
            $results = false;
        }


        return $results;
    }

    public function asController(string $quiz_id) : string|false
    {
        return $this->handle($quiz_id, request()->all());
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
