<?php

namespace App\Aggregates\Candidates;

use App\Exceptions\Candidates\JobPositionException;
use App\StorableEvents\Candidates\JobPositionCreated;
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
        $this->job_type = $event->config['concentration']->value;
        $this->awarded_role = $event->config['awarded_role']->value;
    }

    public function applyQualifiedRoleAdded(QualifiedRoleAdded $event)
    {
        $this->qualified_roles[] = $event->role;
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
}
