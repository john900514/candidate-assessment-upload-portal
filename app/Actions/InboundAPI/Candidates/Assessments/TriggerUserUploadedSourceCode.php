<?php

namespace App\Actions\InboundAPI\Candidates\Assessments;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Models\Assets\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;
use Ramsey\Uuid\Uuid;

class TriggerUserUploadedSourceCode
{
    use AsAction;

    public function rules() : array
    {
        return [
            'assessment_id' => ['bail', 'required', 'exists:assessments,id']
        ];
    }

    public function handle(string $user_id, string $assessment_id) : array | false | string
    {
        $results = false;
        $aggy = UserAggregate::retrieve($user_id);

        // Check if the user is an applicant or fail with string
        if($aggy->isApplicant())
        {
            // Check if the applicant is qualified or fail with string
            if($aggy->getCandidateStatus() == 'qualified-candidate')
            {
                // user activity to log in uploaded_files, and user_file_uploads
                $file_path = "/candidate_assessment/users/{$user_id}/source_codes/";
                $exists_already = UploadedFile::whereFilePath($file_path.$assessment_id.'.zip')->first();
                $madeup_file_upload_id = (!is_null($exists_already)) ? $exists_already->id : Uuid::uuid4()->toString();
                $file_uploaded_date = date('Y-m-d H:i:s');

                $aggy = $aggy->submitSourceCodeUpload($madeup_file_upload_id, $file_uploaded_date, $file_path.$assessment_id.'.zip', $assessment_id);

                // Get Open Job Positions or fail with string
                $jobs = $aggy->getOpenJobPositions();
                if(count($jobs) > 0)
                {
                    $open_jobs_with_assessment_linked = [];
                    foreach ($jobs as $idx => $job_id)
                    {
                        $job_aggy = JobPositionAggregate::retrieve($job_id);
                        if($job_aggy->confirmAssessment($assessment_id))
                        {
                            $user_ass_status = $aggy->getAssessmentStatus($job_id, $assessment_id);
                            if(($user_ass_status['status'] != 'Not Started') && ($user_ass_status['status'] != 'Completed'))
                            {
                                $open_jobs_with_assessment_linked[] = $job_id;
                            }

                        }
                    }

                    if(count($open_jobs_with_assessment_linked) > 0)
                    {
                        foreach ($open_jobs_with_assessment_linked as $idx => $job_id)
                        {
                            // user candidate to change the status of the assessment
                            $payload = [
                                'sourceUploaded' => true,
                                'sourceFileUploadId' => $madeup_file_upload_id,
                                'sourceFileUploadDate' => $file_uploaded_date
                            ];
                            $aggy = $aggy->updateJobAssessmentStatus($job_id, $assessment_id, 'Source Uploaded', $payload);
                        }

                        $aggy->persist();
                        $results = ['success' => true];
                    }
                    else
                    {
                        $results = 'No Open Jobs are linked to this assessment or Applicant completed the assessment.';
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
            return $this->handle($user->id, request()->get('assessment_id'));
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
