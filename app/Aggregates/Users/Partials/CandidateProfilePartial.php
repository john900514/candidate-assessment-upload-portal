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
                        break;

                    case 'Completed':
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
                    /*
                    $total = $this->application_statuses[$job_id]['assessments'][$assessment]['tasksCompleted'];
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

                // @todo - check the status of the application it self
                $total_asses = count($this->application_statuses[$job_id]['assessments']);
                $total_completed = 0;
                foreach($this->application_statuses[$job_id]['assessments'] as $assessment)
                {
                    if($assessment['status'] == 'Completed') $total_completed++;
                }

                if($total_asses == $total_completed) $this->application_statuses[$job_id]['status'] = 'Ready to Apply';
            }
        }
    }

    public function applyCandidateAssessmentTaskStatusUpdated(CandidateAssessmentTaskStatusUpdated $event)
    {
        $this->application_statuses[$event->job_id]['assessments'][$event->assessment_id]['task_statuses'][$event->task_name] = $event->details;
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
                    'status' => 'Not Applied'
                ];
                $job_aggy = JobPositionAggregate::retrieve($job_id);
                $assessments = $job_aggy->getAssessments();
                foreach($assessments as $assessment)
                {
                    $ass_aggy = AssessmentAggregate::retrieve($assessment);
                    $quizzes_reqd = count($ass_aggy->getQuizzes());
                    $tasks_reqd = count($ass_aggy->getTasks());
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
                            'response' => null
                        ];
                    }

                    $quizzes = $ass_aggy->getQuizzes();

                    foreach ($quizzes as $wtf => $bbw)
                    {
                        dd($quizzes);
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
        $this->application_statuses[$event->job_id]['status'] = 'Applied';
        $this->applied_for_job_positions[$event->job_id] = $this->application_statuses[$event->job_id];
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

    public function updateAssessmentTaskStatus(string $job_id, string $assessment_id, string $task_name, string $status) : self
    {
        $assessment_status = $this->getAssessmentStatus($job_id, $assessment_id);
        $task_statuses = $assessment_status['task_statuses'];
        $task = $task_statuses[$task_name];

        if($task['status'] == 'Complete')
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

    public function getCandidateStatus()
    {
        return $this->candidate_status;
    }

    public function hasSubmittedResume() : bool
    {
        return $this->has_submitted_resume;
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
                }
                else
                {
                    $results = false;
                }
            }
        }

        return $results;
    }
}
