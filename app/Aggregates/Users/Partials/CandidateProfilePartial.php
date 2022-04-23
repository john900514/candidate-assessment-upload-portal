<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Users\ApplicantCreated;
use App\StorableEvents\Users\Applicants\ApplicantLinkedToJobPosition;
use App\StorableEvents\Users\Applicants\ApplicantRoleChanged;
use App\StorableEvents\Users\UserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class CandidateProfilePartial extends AggregatePartial
{
    protected string $candidate_status = 'unqualified-candidate';
    protected array $open_job_positions = [];

    protected array $application_statuses = [];
    protected bool $has_submitted_resume = false;

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

    public function applyApplicantLinkedToJobPosition(ApplicantLinkedToJobPosition $event)
    {
        $this->open_job_positions = $event->job_positions;

        foreach ($this->open_job_positions as $idx => $job_id)
        {
            if(!array_key_exists($job_id, $this->application_statuses))
            {
                $this->application_statuses[$job_id] = [];
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
}
