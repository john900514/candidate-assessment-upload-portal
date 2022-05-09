<?php

namespace App\Aggregates\Candidates;

use App\Aggregates\Users\UserAggregate;
use App\Exceptions\Candidates\JobPositionException;
use App\Models\Communication\MailingList;
use App\StorableEvents\Candidates\AssessmentsAddedOrUpdatedToJobPosition;
use App\StorableEvents\Candidates\JobApplications\UsersJobApplicationWasReverted;
use App\StorableEvents\Candidates\JobApplications\UserSubmittedJobApplication;
use App\StorableEvents\Candidates\JobDescription;
use App\StorableEvents\Candidates\JobPositionCreated;
use App\StorableEvents\Candidates\JobPositionUpdated;
use App\StorableEvents\Candidates\QualifiedRoleAdded;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class JobPositionAggregate extends AggregateRoot
{
    protected string|null $job_title = null;
    protected string|null $description = null;
    protected string|null $awarded_role = 'dev';
    protected string|null $job_type = null;
    protected bool $active = false;
    protected array $assessments = [];
    protected array $qualified_roles = [];
    protected array $candidate_users = [];
    protected array $rejected_candidate_users = [];
    protected array $interview_candidate_users = [];
    protected array $hired_users = [];

    public function applyJobPositionCreated(JobPositionCreated $event)
    {
        $this->job_title = $event->config['position'];
        $this->job_type = $event->config['concentration']['value'] ?? $event->config['concentration']->value ?? $event->config['concentration'];
        $this->awarded_role = $event->config['awarded_role']['value'] ?? $event->config['awarded_role']->value ?? $event->config['awarded_role'];
    }

    public function applyQualifiedRoleAdded(QualifiedRoleAdded $event)
    {
        $this->qualified_roles[] = $event->role;
    }

    public function applyJobPositionUpdated(JobPositionUpdated $event)
    {
        $this->job_title = $event->config['position'];
        $this->job_type = $event->config['concentration']['value'] ?? $event->config['concentration']->value ?? $event->config['concentration'];
        $this->awarded_role = $event->config['awarded_role']['value'] ?? $event->config['awarded_role']->value ?? $event->config['awarded_role'];
        $this->active = $event->config['active'];
    }

    public function applyAssessmentsAddedOrUpdatedToJobPosition(AssessmentsAddedOrUpdatedToJobPosition $event)
    {
        $this->assessments = $event->assessments;

        /*
        foreach ($event->assessments as $assessment)
        {
            $exists_already = false;
            foreach($this->assessments as $existing)
            {
                if($existing == $assessment)
                {
                    $exists_already = true;
                    break;
                }
            }
            if(!$exists_already)
            {
                $this->assessments[] = $assessment;
            }
        }
        */
    }

    public function applyJobDescription(JobDescription $event)
    {
        $this->description = $event->desc;
    }

    public function applyUserSubmittedJobApplication(UserSubmittedJobApplication $event)
    {
        $this->candidate_users[$event->user_id] = $event->email;
    }

    public function applyUsersJobApplicationWasReverted(UsersJobApplicationWasReverted $event)
    {
        unset($this->candidate_users[$event->user_id]);
    }

    public function createJobPosition(array $config) : self
    {
        if(!is_null($this->job_title))
        {
            throw JobPositionException::jobPositionAlreadyExists($config['position']);
        }

        $this->recordThat(new JobPositionCreated($this->uuid(), $config));

        return $this;
    }

    public function addQualifiedRole(string $pending_role) : self
    {
        foreach ($this->qualified_roles as $role)
        {
            if($pending_role == $role)
            {
                throw JobPositionException::qualifiedRoleAlreadyAdded($role);
            }
        }

        $this->recordThat(new QualifiedRoleAdded($this->uuid(), $pending_role));

        return $this;
    }

    public function updateJobPosition(array $config) : self
    {
        $this->recordThat(new JobPositionUpdated($this->uuid(), $config));
        return $this;
    }

    public function updateAssessments(array $assessments) : self
    {
        $this->recordThat(new AssessmentsAddedOrUpdatedToJobPosition($this->uuid(), $assessments));
        return $this;
    }

    public function updateDescription(string $desc) : self
    {
        $this->recordThat(new JobDescription($this->uuid(), $desc));
        return $this;
    }

    public function submitUserJobApplication(string $user_id, string $email) : self
    {
        if(array_key_exists($user_id, $this->candidate_users))
        {
            throw JobPositionException::userCannotBeAddedToCandidates('User is already a candidate.');
        }
        elseif(array_key_exists($user_id, $this->rejected_candidate_users))
        {
            throw JobPositionException::userCannotBeAddedToCandidates('User is was already rejected and cannot re-apply for this position.');
        }
        elseif(array_key_exists($user_id, $this->interview_candidate_users))
        {
            throw JobPositionException::userCannotBeAddedToCandidates('This candidate is already pending an interview for this position.');
        }
        elseif(array_key_exists($user_id, $this->hired_users))
        {
            throw JobPositionException::userCannotBeAddedToCandidates('Hired users cannot reapply. Please wait to be onboarded.');
        }

        $this->recordThat(new UserSubmittedJobApplication($this->uuid(), $user_id, $email));

        return $this;
    }

    public function removeUserFromCandidates(string $user_id) : self
    {
        if(!array_key_exists($user_id, $this->candidate_users))
        {
            throw JobPositionException::userCannotBeRemovedFromCandidates('User is not in candidate list');
        }

        $this->recordThat(new UsersJobApplicationWasReverted($this->uuid(), $user_id));

        return $this;
    }

    public function getAssessments() : array
    {
        $results = [];
        foreach($this->assessments as $assessment)
        {
            if(!array_key_exists($assessment, $results))
            {
                $results[$assessment] = $assessment;
            }
        }

        return $results;
    }

    public function confirmAssessment(string $assessment_id) : bool
    {
        $results = false;
        foreach ($this->assessments as $assessment)
        {
            if($results = ($assessment == $assessment_id))
            {
                break;
            }
        }
        return $results;
    }

    public function getDesc() : string | null
    {
        return $this->description;
    }

    public function getJobTitle() : string | null
    {
        return $this->job_title;
    }

    public function getConcentration() : string | null
    {
        return $this->job_type;
    }

    public function getAssociatedMailingLists() : array
    {
        $results = [];

        $lists_models = MailingList::whereConcentration($this->job_type)->get();

        if(count($lists_models) > 0)
        {
            $results = $lists_models->toArray();
        }

        return $results;
    }
}
