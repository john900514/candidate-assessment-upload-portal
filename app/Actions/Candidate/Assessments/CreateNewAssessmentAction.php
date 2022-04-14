<?php

namespace App\Actions\Candidate\Assessments;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Exceptions\Candidates\AssessmentException;
use Lorisleiva\Actions\Concerns\AsAction;
use Ramsey\Uuid\Uuid;

class CreateNewAssessmentAction
{
    use AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {
        // ...
    }

    public function handle(array $payload, bool $has_quizzes, bool $has_source_code) : string | false
    {
        $results = false;

        try {
            if(backpack_user()->can('create_assessments'))
            {
                $results = Uuid::uuid4()->toString();
                AssessmentAggregate::retrieve($results)
                    ->createNewAssessment($payload, $has_quizzes, $has_source_code)
                    ->persist();
            }

        }
        catch(AssessmentException $e)
        {
            $results = false;
        }

        return $results;
    }

    public function asController()
    {
        return $this->handle();
    }

    public function jsonResponse(string|false $result)
    {
        $results = ['success' => false];
        $code = 500;

        if($result)
        {
            $results = ['success' => true, 'token' => $result];
            $code = 200;
        }

        return response($results, $code);
    }

    public function htmlResponse(string|false $result)
    {

    }
}
