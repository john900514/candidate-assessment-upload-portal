<?php

namespace App\Aggregates\Users\Partials;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Exceptions\Candidates\JobPositionException;
use App\StorableEvents\Candidates\Assessments\CandidateAssessmentTaskStatusUpdated;
use App\StorableEvents\Candidates\Assessments\CandidateJobAssessmentStatusUpdated;
use App\StorableEvents\Candidates\Assessments\SourceCodeSubmittedForAssessment;
use App\StorableEvents\Users\ApplicantCreated;
use App\StorableEvents\Users\Applicants\ApplicantLinkedToJobPosition;
use App\StorableEvents\Users\Applicants\ApplicantRoleChanged;
use App\StorableEvents\Users\Applicants\ApplicantSubmittedJobApplication;
use App\StorableEvents\Users\Applicants\ApplicantUploadedResume;
use App\StorableEvents\Users\UserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class CandidateProfilePartial extends AggregatePartial
{
    protected string $candidate_status = 'unqualified-candidate';
    protected array $open_job_positions = [];
    protected array $applied_for_job_positions = [];

    protected array $application_statuses = [];
    protected bool $has_submitted_resume = false;
    protected string|null $resume_path = null;

    public function applyUserCreated(UserCreated $event)
    {
        switch($event->role)
        {
            case 'FE_CANDIDATE':
            case 'BE_CANDIDATE':
            case 'FS_CANDIDATE':
            case 'MGNT_CANDIDATE':
                $this->candidate_status = 'qualified-candidate';
                break;

            case 'APPLICANT':
                $this->candidate_status = 'unqualified-candidate';
                break;

            default:
                $this->candidate_status = 'non-candidate';
        }

    }

    public function applyApplicantCreated(ApplicantCreated $event)
    {
        switch(strtoupper($event->role))
        {
            case 'FE_CANDIDATE':
            case 'BE_CANDIDATE':
            case 'FS_CANDIDATE':
            case 'MGNT_CANDIDATE':
                $this->candidate_status = 'qualified-candidate';
                break;

            case 'APPLICANT':
                $this->candidate_status = 'unqualified-candidate';
                break;

            default:
                $this->candidate_status = 'non-candidate';
        }
    }

    public function applyApplicantRoleChanged(ApplicantRoleChanged $event)
    {
        switch($event->role)
        {
            case 'FE_CANDIDATE':
            case 'BE_CANDIDATE':
            case 'FS_CANDIDATE':
            case 'MGNT_CANDIDATE':
                $this->candidate_status = 'qualified-candidate';
                break;

            case 'APPLICANT':
                $this->candidate_status = 'unqualified-candidate';
                break;

            default:
                $this->candidate_status = 'non-candidate';
        }
    }

    public function applyCandidateJobAssessmentStatusUpdated(CandidateJobAssessmentStatusUpdated $event)
    {
        if(array_key_exists($event->job_id, $this->application_statuses))
        {
            $job_id = $event->job_id;
            if(array_key_exists($event->assessment_id, $this->application_statuses[$job_id]['assessments']))
            {
                $assessment = $event->assessment_id;
                $this->application_statuses[$job_id]['assessments'][$assessment]['status'] = $event->new_status;

                switch($event->new_status)
                {
                    case 'Not Started':
                        $this->application_statuses[$job_id]['assessments'][$assessment]['badge'] = 'badge-error';
                        break;

                    case 'Started':
                        $this->application_statuses[$job_id]['assessments'][$assessment]['badge'] = 'badge-info';
                        $this->application_statuses[$job_id]['assessments'][$assessment]['time_started'] = $event->createdAt();
                        $this->application_statuses[$job_id]['assessments'][$assessment]['time_expires'] = date('Y-m-d H:i:s', strtotime($event->createdAt()." + 4HOUR"));
                        break;

                    case 'Complete':
                        $this->application_statuses[$job_id]['assessments'][$assessment]['badge'] = 'badge-success';
                        break;

                    default:
                        $this->application_statuses[$job_id]['assessments'][$assessment]['badge'] = 'badge-warn';
                }

                if(array_key_exists('quizzesCompleted', $event->details))
                {
                    /*
                    $total = $this->application_statuses[$job_id]['assessments'][$assessment]['quizzesReqd'];
                    $total -= $event->details['quizzesCompleted'];
                    $this->application_statuses[$job_id]['assessments'][$assessment]['quizzesCompleted'] = $total;
                    */
                }

                if(array_key_exists('tasksCompleted', $event->details))
                {
                    $total = $this->application_statuses[$job_id]['assessments'][$assessment];
                    //dd($total);
                    /*
                    ['tasksCompleted'];
                    $total -= $event->details['tasksCompleted'];
                    $this->application_statuses[$job_id]['assessments'][$assessment]['tasksCompleted'] = $total;
                    */
                }

                if(array_key_exists('sourceUploaded', $event->details))
                {
                    $this->application_statuses[$job_id]['assessments'][$assessment]['sourceUploaded'] = true;
                    $this->application_statuses[$job_id]['assessments'][$assessment]['sourceFileUploadId'] = $event->details['sourceFileUploadId'];
                    $this->application_statuses[$job_id]['assessments'][$assessment]['sourceFileUploadDate'] = $event->details['sourceFileUploadDate'];
                }

                // do a check on requirements to see if we can set the status to Completed actually!
                $status_review = $this->application_statuses[$job_id]['assessments'][$assessment];
                if($status_review['quizzesCompleted'] && $status_review['tasksCompleted'] && $status_review['sourceUploaded'])
                {
                    $this->application_statuses[$job_id]['assessments'][$assessment]['status'] = 'Completed';
                    $this->application_statuses[$job_id]['assessments'][$assessment]['badge'] = 'badge-success';
                }

                $total_completed = 0;

                foreach ($status_review['task_statuses'] as $task_name => $task_status)
                {
                    /**
                     * 1. If tasksReqd > 0, check on all the tasks
                     * 2. if quizzesReq > 0, check on the quizzes
                     * 3. If source code needed, check that the installer was downloaded.
                     */
                    if(!array_key_exists('required', $task_status))
                    {
                        dd(count($this->application_statuses[$job_id]['assessments']), $task_status);
                    }

                }

                $this->application_statuses[$job_id]['last_updated'] = $event->createdAt();
                //if($total_asses == $total_completed) $this->application_statuses[$job_id]['status'] = 'Ready to Apply';
            }
        }
    }

    public function applyCandidateAssessmentTaskStatusUpdated(CandidateAssessmentTaskStatusUpdated $event)
    {
        $this->application_statuses[$event->job_id] = $this->getAssessmentStatus($event->job_id);
        $this->application_statuses[$event->job_id]['assessments'][$event->assessment_id]['task_statuses'][$event->task_name] = $event->details;
        $this->application_statuses[$event->job_id]['assessments'][$event->assessment_id]['last_updated'] = $event->createdAt();
        $this->application_statuses[$event->job_id]['last_updated'] = $event->createdAt();
    }

    public function applyApplicantLinkedToJobPosition(ApplicantLinkedToJobPosition $event)
    {
        $this->open_job_positions = $event->job_positions;

        foreach ($this->open_job_positions as $idx => $job_id)
        {
            if(!array_key_exists($job_id, $this->application_statuses))
            {
                $this->application_statuses[$job_id] = [
                    'assessments' => [],
                    'status' => 'Not Applied',
                    'last_updated' => $event->createdAt()
                ];
                $job_aggy = JobPositionAggregate::retrieve($job_id);
                $assessments = $job_aggy->getAssessments();
                foreach($assessments as $assessment)
                {
                    $ass_aggy = AssessmentAggregate::retrieve($assessment);
                    $quizzes_reqd = count($ass_aggy->getQuizzes());
                    $total_tasks = $ass_aggy->getTasks();
                    $tasks_reqd = 0;
                    foreach ($total_tasks as $col => $val)
                    {
                        if($val['required']) $tasks_reqd++;
                    }

                    $has_source = $ass_aggy->hasCodeWork();
                    $this->application_statuses[$job_id]['assessments'][$assessment] = [
                        'status' => 'Not Started',
                        'badge'  => 'badge-error',
                        'quizzesReqd' => $quizzes_reqd,
                        'quizzesRemaining' => $quizzes_reqd,
                        'tasksReqd' => $tasks_reqd,
                        'tasksRemaining' => $tasks_reqd,
                        'sourceReqd' => $has_source,
                        'quizzesCompleted' => ($quizzes_reqd == 0),
                        'tasksCompleted' => ($tasks_reqd == 0),
                        'sourceUploaded' => ($has_source == false),
                        'task_statuses' => [],
                        'quiz_statuses' => [],
                    ];

                    $tasks = $ass_aggy->getTasks();
                    foreach ($tasks as $task_name => $task)
                    {
                        $this->application_statuses[$job_id]['assessments'][$assessment]['task_statuses'][$task_name] = [
                            'status' => 'Incomplete',
                            'badge' => 'badge-error',
                            'date_started' => null,
                            'date_completed' => null,
                            'response' => null,
                            'required' => $task['required']
                        ];
                    }

                    $quizzes = $ass_aggy->getQuizzes();

                    foreach ($quizzes as $wtf => $bbw)
                    {
                        //dd($quizzes);
                    }
                }

            }
        }
    }

    public function applyApplicantUploadedResume(ApplicantUploadedResume $event)
    {
        $this->has_submitted_resume = true;
        $this->resume_path = $event->path;
    }

    public function applyApplicantSubmittedJobApplication(ApplicantSubmittedJobApplication $event)
    {
        $status = $this->getAssessmentStatus($event->job_id);
        $status['status'] = 'Applied';
        $this->applied_for_job_positions[$event->job_id] = $this->application_statuses[$event->job_id] = $status;

        foreach ($this->open_job_positions as $idx => $job_id)
        {
            if($job_id == $event->job_id)
            {
                unset($this->open_job_positions[$idx]);
                break;
            }
        }
    }

    public function updateCandidateRole(string $role) : self
    {
        $this->recordThat(new ApplicantRoleChanged($this->aggregateRootUuid(), $role));
        return $this;
    }

    public function updateCandidatesAvailablePositions(array $positions) : self
    {
        $this->recordThat(new ApplicantLinkedToJobPosition($this->aggregateRootUuid(),$positions ));
        return $this;
    }

    public function submitResume(string $path) : self
    {
        $this->recordThat(new ApplicantUploadedResume($this->aggregateRootUuid(), $path));
        return $this;
    }

    public function submitSourceCodeUpload(string $file_upload_id, string $file_upload_date, string $path, string $assessment_id) : self
    {
        $this->recordThat(new SourceCodeSubmittedForAssessment($this->aggregateRootUuid(), $file_upload_id, $path, $file_upload_date, $assessment_id));
        return $this;
    }

    public function updateJobAssessmentStatus(string $job_id, string $assessment_id, string $status, array $misc = []) : self
    {
        $this->recordThat(new CandidateJobAssessmentStatusUpdated($this->aggregateRootUuid(), $job_id, $assessment_id, $status, $misc));
        return $this;
    }

    public function updateAssessmentTaskStatus(string $job_id, string $assessment_id, string $task_name, string $status, string|null $response) : self
    {
        $assessment_status = $this->getAssessmentStatus($job_id, $assessment_id);
        $task_statuses = $assessment_status['task_statuses'];
        $task = $task_statuses[$task_name];

        if(($task['status'] == 'Completed') || ($task['status'] == 'Complete'))
        {
            // @todo - throw error
            return $this;
        }

        switch($status)
        {
            case 'Started':
                $task['status'] = $status;
                $task['badge'] = 'badge-warning';
                $task['date_started'] = date('Y-m-d H:i:s');
                break;

            case 'Stopped':
                $task['status'] = $status;
                $task['badge'] = 'badge-error';
                break;

            case 'finished':
                $task['status'] = 'Complete';
                $task['badge'] = 'badge-success';
                $task['response'] = $response;
                $task['date_completed'] = date('Y-m-d H:i:s');
                break;
        }

        $this->recordThat(new CandidateAssessmentTaskStatusUpdated($this->aggregateRootUuid(), $job_id, $assessment_id, $task_name, $task));

        return $this;
    }

    public function logJobApplication(string $job_id) : self
    {
        $found = false;
        foreach ($this->open_job_positions as $idx => $job_uuid)
        {
            if($found = ($job_id == $job_uuid))
            {
                break;
            }
        }

        if(!$found)
        {
            // remove the user from the list of job Candidates before throwing exception
            JobPositionAggregate::retrieve($job_id)->removeUserFromCandidates($this->aggregateRootUuid())->persist();
            throw JobPositionException::userCannotBeAddedToCandidates('This job is not available for this user to Apply to.');
        }

        $this->recordThat(new ApplicantSubmittedJobApplication($this->aggregateRootUuid(), $job_id, date('Y-m-d H:i:s')));

        return $this;
    }

    public function getOpenJobPositions() : array
    {
        return  $this->open_job_positions;
    }

    public function getPositionsSubmitted() : array
    {
        return $this->applied_for_job_positions;
    }

    public function getCandidateStatus()
    {
        return $this->candidate_status;
    }

    public function hasSubmittedResume() : bool
    {
        return $this->has_submitted_resume;
    }

    public function getResumePath() : null|string
    {
        return $this->resume_path;
    }

    public function getAssessmentStatus(string $job_id, string|null $assessment_id = null) : false|array
    {
        $results = false;

        if(array_key_exists($job_id, $this->application_statuses))
        {
            $results = $this->application_statuses[$job_id];

            if(!is_null($assessment_id))
            {
                if(array_key_exists($assessment_id, $this->application_statuses[$job_id]['assessments']))
                {
                    $results = $this->application_statuses[$job_id]['assessments'][$assessment_id];

                    // Eval the requirements progress
                    // First check Tasks
                    $tasks_completed = false;
                    $quizzes_completed = false;
                    $source_uploaded = $results['sourceUploaded'] ?? true;

                    $requires_tasks = ($results['tasksReqd'] === true) || ($results['tasksReqd'] > 0);
                    $requires_quizzes = ($results['quizzesReqd'] === true) || ($results['quizzesReqd'] > 0);

                    if($requires_tasks)
                    {
                        if(array_key_exists('task_statuses', $results))
                        {
                            $col_res = collect($results['task_statuses']);
                            $no_of_tasks_reqd = $col_res->where('required', '=', true)->count();
                            $no_of_tasks_completed = $col_res->where('status', '=', 'Complete')->where('required', '=', true)->count();
                            $tasks_completed = $results['tasksCompleted'] = $no_of_tasks_reqd == $no_of_tasks_completed;
                            $results['tasksRemaining'] = $no_of_tasks_reqd - $no_of_tasks_completed;
                        }
                    }
                    else
                    {
                        $tasks_completed = true;
                    }

                    if($requires_quizzes)
                    {
                        dd($results, 'farkity');
                    }
                    else
                    {
                        $quizzes_completed = true;
                    }

                    $assessment_complete = ($tasks_completed && $source_uploaded) && $quizzes_completed;

                    if($assessment_complete)
                    {
                        $results['status'] = 'Complete';
                        $results['badge'] = 'badge-success';
                    }
                }
                else
                {
                    $results = false;
                }
            }
            else
            {
                $assessments_completed = 0;
                $assessments_to_complete = count($results['assessments']);
                foreach ($results['assessments'] as $ass_id => $user_data)
                {
                    $progress = $this->getAssessmentStatus($job_id, $ass_id);
                    if(($progress['status'] == 'Completed') || ($progress['status'] == 'Complete'))
                    {
                        $assessments_completed++;
                        $results['assessments'][$ass_id] = $progress;
                    }
                }

                if($assessments_to_complete == $assessments_completed)
                {
                    $results['status'] = 'Ready to Apply';
                }
            }
        }

        return $results;
    }
}
