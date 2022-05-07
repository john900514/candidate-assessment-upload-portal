<?php

namespace App\Aggregates\Users;

use App\Aggregates\Users\Partials\AccessTokenPartial;
use App\Aggregates\Users\Partials\CandidateProfilePartial;
use App\Aggregates\Users\Partials\EmployeeProfilePartial;
use App\Aggregates\Users\Partials\UserActivityPartial;
use App\Exceptions\Users\UserAuthException;
use App\StorableEvents\Users\Activity\Account\PasswordUpdated;
use App\StorableEvents\Users\Activity\Account\UserVerified;
use App\StorableEvents\Users\Activity\Email\UserSentWelcomeEmail;
use App\StorableEvents\Users\ApplicantCreated;
use App\StorableEvents\Users\Applicants\ApplicantRoleChanged;
use App\StorableEvents\Users\UserCreated;
use App\StorableEvents\Users\UserUpdated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregate extends AggregateRoot
{
    protected string|null $email = null;
    protected string|null $first_name = null;
    protected string|null $last_name = null;
    protected string|null $role = 'applicant';
    protected bool $verified = false;

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
        $this->last_name = $event->details['last_name'];
        $this->email = $event->details['email'];
        $this->role  = $event->role;

    }
    public function applyUserUpdated(UserUpdated $event)
    {
        $this->first_name = $event->details['first_name'];
        $this->last_name = $event->details['last_name'];
        $this->email = $event->details['email'];
    }

    public function applyUserVerified(UserVerified $event)
    {
        $this->verified = true;
    }

    public function applyApplicantCreated(ApplicantCreated $event)
    {
        $this->first_name = $event->details['first_name'];
        $this->last_name = $event->details['last_name'];
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

        switch($role)
        {
            case 'applicant':
            case 'fs_candidate':
            case 'fe_candidate':
            case 'be_candidate':
            case 'mgnt_candidate':
                $this->recordThat(new ApplicantCreated($this->uuid(), $details, $role));
                break;

            default:
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

    public function sendWelcomeEmail(string $status) : self
    {
        if($this->verified)
        {
            throw UserAuthException::userAlreadyVerified();
        }

        $this->activity->sendWelcomeEmail($status);
        return $this;
    }

    public function downloadSourceCodeInstaller()
    {
        $this->activity->downloadSourceCodeInstaller();
        return $this;
    }

    public function updateUser(array $user_data) : self
    {
        $this->recordThat(new UserUpdated($this->uuid(), $user_data));
        return $this;
    }

    public function updatePassword(string $enc_pw) : self
    {
        $this->recordThat(new PasswordUpdated($this->uuid(), $enc_pw));
        return $this;
    }

    public function verifyUser(string $date) : self
    {
        if($this->verified)
        {
            throw UserAuthException::userAlreadyVerified();
        }

        $this->recordThat(new UserVerified($this->uuid(), $date));

        return $this;
    }

    public function submitResume(string $path) : self
    {
        if(!$this->isApplicant())
        {
            UserAuthException::resumeNotNeeded();
        }

        $this->candidate_profile->submitResume($path);

        return $this;
    }

    public function updateJobAssessmentStatus(string $job_id, string $assessment_id, string $status, array $misc = []) : self
    {
        $this->candidate_profile->updateJobAssessmentStatus( $job_id,  $assessment_id,  $status, $misc);
        return $this;
    }

    public function submitSourceCodeUpload(string $file_upload_id, string $file_upload_date, string $path, string $assessment_id) : self
    {
        $this->candidate_profile->submitSourceCodeUpload($file_upload_id, $file_upload_date, $path, $assessment_id);
        return $this;
    }

    public function logJobApplication(string $job_id) : self
    {
        $this->candidate_profile->logJobApplication($job_id);
        return $this;
    }

    public function fireEmailToThisUser(string $mail_class, array $payload) : self
    {
        $this->activity->fireEmailToThisUser($mail_class, $payload);
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

    public function getLastName() : string|null
    {
        return $this->last_name;
    }

    public function getEmail() : string|null
    {
        return $this->email;
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

    public function isUserVerified() : bool
    {
        return $this->verified;
    }

    public function hasSubmittedResume() : bool
    {
        return $this->candidate_profile->hasSubmittedResume();
    }

    public function hasDownloadedInstaller() : bool
    {
        return $this->activity->hasDownloadedInstaller();
    }
    public function getAssessmentStatus(string $job_id, string|null $assessment_id = null) : false|array
    {
        return $this->candidate_profile->getAssessmentStatus($job_id, $assessment_id);
    }

}
