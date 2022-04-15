<?php

namespace App\Actions\InboundAPI\Assets\Files\SourceCode;

use App\Aggregates\Assets\FileUploadAggregate;
use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Models\Assets\SourceCodeUpload;
use Lorisleiva\Actions\Concerns\AsAction;

class DownloadSourceCode
{
    use AsAction;

    public function handle(string $user_id, string $source_code_id) : array | false | string
    {
        $aggy = UserAggregate::retrieve($user_id);

        // Check if the user is an applicant or fail with string
        if($aggy->isApplicant())
        {
            // Check if the applicant is qualified or fail with string
            if($aggy->getCandidateStatus() == 'qualified-candidate')
            {
                // Get Open Job Positions or fail with string
                $jobs = $aggy->getOpenJobPositions();
                if(count($jobs) > 0)
                {
                    // Get array of assessments for each or fail with string
                    $assessments = [];
                    foreach ($jobs as $idx => $job_id)
                    {
                        $job_aggy = JobPositionAggregate::retrieve($job_id);

                        $job_tests = $job_aggy->getAssessments();
                        if(count($job_tests) > 0)
                        {
                            foreach ($job_tests as $assessment_id)
                            {
                                $assessments[] = $assessment_id;
                            }
                        }


                    }

                    if(count($assessments) > 0)
                    {
                        $found = false;
                        // Foreach assessment get the ones with a source code requirement or fail with string
                        foreach ($assessments as $idx => $assessment)
                        {
                            $ass_aggy = AssessmentAggregate::retrieve($assessment);

                            if($ass_aggy->hasCodeWork())
                            {
                                if($code_id = $ass_aggy->getCodeWorkId())
                                {
                                    $record = SourceCodeUpload::whereId($code_id)
                                        ->whereFileId($source_code_id)->first();
                                    $found = !is_null($record);
                                    break;
                                }
                            }
                        }

                        if($found)
                        {
                            $file_aggy = FileUploadAggregate::retrieve($source_code_id);
                            if($url = $file_aggy->getTemporaryUrl())
                            {
                                $results = ['url' => $url];
                            }
                            else
                            {
                                $results = 'File not Available';
                            }
                        }
                        else
                        {
                            $results = 'File not Found';
                        }
                    }
                    else
                    {
                        $results = 'A Job with a Source Code Assessment must be available for you to apply for.';
                    }
                }
                else
                {
                    $results = 'A Job with a Source Code Assessment must be available for you to apply for.';
                }
            }
            else
            {
                $results = 'Access is for Qualified Candidates.';
            }
        }
        else
        {
            $results = 'Access is for Candidates.';
        }

        return $results;
    }

    public function asController()
    {
        backpack_auth()->login(auth()->user());
        if(!is_null($user = backpack_user()))
        {
            if(request()->has('source_code_id'))
            {
                return $this->handle($user->id, request()->get('source_code_id'));
            }
            else
            {
                return 'Missing Source Code ID';
            }
        }
        else
        {
            return 'Invalid Credentials or you Need you login from the API.';
        }

    }

    public function jsonResponse(string|false|array $result)
    {
        $results = false;
        $code = 500;

        if(is_string($result))
        {
            $results = ['error' => $result];
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
