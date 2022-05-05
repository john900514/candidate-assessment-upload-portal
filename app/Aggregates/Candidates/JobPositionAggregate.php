<?php

namespace App\Aggregates\Candidates;

use App\Exceptions\Candidates\JobPositionException;
use App\StorableEvents\Candidates\AssessmentsAddedOrUpdatedToJobPosition;
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

    public function applyJobPositionCreated(JobPositionCreated $event)
    {
        $this->job_title = $event->config['position'];
        $this->job_type = $event->config['concentration']->value ?? $event->config['concentration']['value'];
        $this->awarded_role = $event->config['awarded_role']->value ?? $event->config['awarded_role']['value'];
    }

    public function applyQualifiedRoleAdded(QualifiedRoleAdded $event)
    {
        $this->qualified_roles[] = $event->role;
    }

    public function applyJobPositionUpdated(JobPositionUpdated $event)
    {
        $this->job_title = $event->config['position'];
        $this->job_type = $event->config['concentration']->value ?? $event->config['concentration']['value'];
        $this->awarded_role = $event->config['awarded_role']->value ?? $event->config['awarded_role']['value'];
        $this->active = $event->config['active'];
    }

    public function applyAssessmentsAddedOrUpdatedToJobPosition(AssessmentsAddedOrUpdatedToJobPosition $event)
    {
        //$this->assessments = $event->assessments;

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
    }

    public function applyJobDescription(JobDescription $event)
    {
        $this->description = $event->desc;
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
}
