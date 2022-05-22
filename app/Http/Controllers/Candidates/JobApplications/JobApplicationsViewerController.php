<?php

namespace App\Http\Controllers\Candidates\JobApplications;

use App\Actions\Users\Dashboards\JobApplicationDashboard;
use App\Aggregates\Assets\FileUploadAggregate;
use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Http\Controllers\Controller;
use App\Models\Assets\UserFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationsViewerController extends Controller
{
    public function show(string $candidate_user_id)
    {
        if(backpack_user()->can('view_candidates'))
        {
            if(request()->has('job'))
            {
                $user_aggy = UserAggregate::retrieve($candidate_user_id);
                //check that the $candidate is a candidate
                if($user_aggy->isApplicant())
                {
                    $job_id = request()->get('job');
                    $job_aggy = JobPositionAggregate::retrieve($job_id);
                    // check that the $candidate has applied for this job
                    if(array_key_exists($user_aggy->uuid(), $job_aggy->getPendingCandidates()))
                    {
                        $candidate_name = $user_aggy->getFirstName()." ".$user_aggy->getLastName();
                        $resume = $user_aggy->getResumePath();

                        $data = [
                            'breadcrumbs' =>  [
                                'Dashboard' => url(config('backpack.base.route_prefix'), 'dashboard'),
                                'Candidates' => false,
                                'Job Applications' => false,
                                $candidate_name => false,
                            ],
                            'candidate_name' => $candidate_name,
                            'candidate_email' => $user_aggy->getEmail(),
                            'candidate_dept' => $user_aggy->getDepartment(),
                            'register_date' => $user_aggy->getVerificationDate(),
                            'resume_url' => Storage::disk('s3')->temporaryUrl($resume, now()->addMinutes(10)),
                            'job_title' => $job_aggy->getJobTitle(),
                            'assessments' => []
                        ];

                        $data['widgets']['before_content'] = [JobApplicationDashboard::run($job_aggy, $user_aggy)];
                        if(count($user_aggy->getAssessmentStatus($job_id)['assessments']) > 0)
                        {
                            $curated_assessments = [];
                            foreach ($user_aggy->getAssessmentStatus($job_id)['assessments'] as $ass_id => $user_details)
                            {
                                $payload = [];
                                $assy_mcgee = AssessmentAggregate::retrieve($ass_id);

                                // Get the Assessment Name
                                $payload['name'] = $assy_mcgee->getName();
                                // Get the data completed
                                $payload['completed'] = date('m/d/Y', $user_details['last_updated']->getTimestamp());

                                // Check if there is source code
                                $payload['source_url'] = '';
                                if($payload['source'] = $user_details['sourceReqd'])
                                {
                                    // @todo - fix the backend to make this line of code do the work.
                                    //$file_aggy = FileUploadAggregate::retrieve($user_details['sourceFileUploadId']);
                                    $file_record = UserFileUpload::whereFileId($user_details['sourceFileUploadId'])
                                        ->whereUserId($candidate_user_id)->whereActive(1)
                                        ->with('uploaded_file')
                                        ->first();
                                    // Get the Source code URL
                                    if(!is_null($file_record))
                                    {
                                        $object_path = $file_record->uploaded_file->file_path;
                                        $url = Storage::disk('s3')->temporaryUrl($object_path, now()->addMinutes(10));
                                        $payload['source_url'] = $url;

                                    }
                                }

                                // Get the array of tasks
                                $payload['tasks'] = [];//$assy_mcgee->getTasks();

                                // For each task get the questions and the answers
                                foreach($assy_mcgee->getTasks() as $task_name => $details)
                                {
                                    $user_stat = $user_details['task_statuses'][$task_name];
                                    $payload['tasks'][] = [
                                        'name' => $task_name,
                                        'required' => $user_stat['required'],
                                        'completed' => $user_stat['date_completed'] ?? 'Incomplete',
                                        'desc' => $details['task_description'],
                                        'response' => $user_stat['response'] ?? 'Not Answered'
                                    ];
                                }

                                //Place all the above data in a pretty array and place it into $data['assessments'][]
                                $data['assessments'][] = $payload;
                            }
                        }

                        return view('cms-custom-pages.candidates.job-applications.application-viewer-dashboard', $data);
                    }
                }
                else
                {
                    return view('errors.403');
                }
            }
        }
        else
        {
            return view('errors.401');
        }


        return view('errors.500');
    }
}
