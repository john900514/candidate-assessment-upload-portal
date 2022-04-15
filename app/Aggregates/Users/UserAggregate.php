<?php

namespace App\Aggregates\Users;

use App\Aggregates\Users\Partials\AccessTokenPartial;
use App\Aggregates\Users\Partials\CandidateProfilePartial;
use App\Aggregates\Users\Partials\EmployeeProfilePartial;
use App\Aggregates\Users\Partials\UserActivityPartial;
use App\Exceptions\Users\UserAuthException;
use App\StorableEvents\Users\ApplicantCreated;
use App\StorableEvents\Users\Applicants\ApplicantRoleChanged;
use App\StorableEvents\Users\UserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregate extends AggregateRoot
{
    protected string|null $email = null;
    protected string|null $first_name = null;
    protected string|null $role = 'applicant';

    protected EmployeeProfilePartial $employee_profile;
    protected CandidateProfilePartial $candidate_profile;
    protected AccessTokenPartial $access_token;
    protected UserActivityPartial $activity;

    public function __construct()
    {
        $this->employee_profile = new EmployeeProfilePartial($this);
        $this->candidate_profile = new CandidateProfilePartial($this);
        $this->access_token = new AccessTokenPartial($this);
        $this->activity = new UserActivityPartial($this);
    }

    public function applyUserCreated(UserCreated $event)
    {
        $this->first_name = $event->details['first_name'];
        $this->email = $event->details['email'];
        $this->role  = $event->role;
    }
    public function applyApplicantCreated(ApplicantCreated $event)
    {
        $this->first_name = $event->details['first_name'];
        $this->email = $event->details['email'];
        $this->role  = $event->role;
    }

    public function applyApplicantRoleChanged(ApplicantRoleChanged $event)
    {
        $this->role  = $event->role;
    }

    public function createUser(array $details, string $role = 'applicant') : self
    {
        if(!is_null($this->email))
        {
            throw UserAuthException::userAlreadyExists($details['email']);
        }

        if($role == 'applicant')
        {
            $this->recordThat(new ApplicantCreated($this->uuid(), $details, $role));
        }
        else
        {
            $this->recordThat(new UserCreated($this->uuid(), $details, $role));
        }


        return $this;
    }

    public function grantAccessToken() : self
    {
        $this->access_token->grantAccessToken();
        return $this;
    }

    public function updateCandidateRole(string $role) : self
    {
        $this->candidate_profile->updateCandidateRole($role);
        return $this;
    }

    public function updateCandidatesAvailablePositions(array $positions) : self
    {
        $this->candidate_profile->updateCandidatesAvailablePositions($positions);
        return $this;
    }

    public function downloadSourceCodeInstaller()
    {
        $this->activity->downloadSourceCodeInstaller();
        return $this;
    }

    public function getAccessToken() : string | false
    {
        return $this->access_token->getAccessToken();
    }

    public function getRole() : string | null
    {
        return $this->role;
    }

    public function getFirstName() : string|null
    {
        return $this->first_name;
    }

    public function getOpenJobPositions() : array
    {
        return $this->candidate_profile->getOpenJobPositions();
    }

    public function getCandidateStatus()
    {
        return $this->candidate_profile->getCandidateStatus();
    }
    public function isApplicant()
    {
        return $this->candidate_profile->getCandidateStatus() != 'non-candidate';
    }

    public function isEmployee()
    {
        return $this->employee_profile->getEmployeeStatus() == 'employee';
    }
}
