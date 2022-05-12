<?php

namespace App\Actions\Candidate\Assessments\Tasks;

use Lorisleiva\Actions\Concerns\AsAction;

class SubmitTaskResponse
{
    use AsAction;

    public function handle(string $assessment_id, string $response)
    {
        // ...
    }
}
