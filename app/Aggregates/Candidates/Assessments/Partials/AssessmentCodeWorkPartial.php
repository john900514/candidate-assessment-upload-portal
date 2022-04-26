<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use App\Exceptions\Candidates\AssessmentException;
use App\StorableEvents\Candidates\Assessments\SourceCodeFileAddedToAssessment;
use App\StorableEvents\Candidates\Assessments\Tasks\NewTaskCreated;
use App\StorableEvents\Candidates\Assessments\Tasks\TaskDeactivated;
use App\StorableEvents\Candidates\Assessments\Tasks\TaskReactivated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AssessmentCodeWorkPartial extends AggregatePartial
{
    protected string|null $source_code_uuid = null;
    protected array $tasks = [];
    protected array $deactivated_tasks = [];

    public function applySourceCodeFileAddedToAssessment(SourceCodeFileAddedToAssessment $event)
    {
        $this->source_code_uuid = $event->source_id;
    }

    public function applyNewTaskCreated(NewTaskCreated $event)
    {
        $this->tasks[$event->task_data['task_name']] = $event->task_data;
    }

    public function applyTaskDeactivated(TaskDeactivated $event)
    {
        $task_data = $this->tasks[$event->name];
        $this->deactivated_tasks[$event->name] = $task_data;
        unset($this->tasks[$event->name]);
    }

    public function applyTaskReactivated(TaskReactivated $event)
    {
        $task_data = $this->deactivated_tasks[$event->name];
        $this->tasks[$event->name] = $task_data;
        unset($this->deactivated_tasks[$event->name]);
    }

    public function addSourceCodeRecord(string $source_id) : self
    {
        if(!is_null($this->source_code_uuid))
        {
            throw AssessmentException::sourceCodeAlreadyAdded();
        }

        $this->recordThat(new SourceCodeFileAddedToAssessment($this->aggregateRootUuid(), $source_id));

        return $this;
    }

    public function getSourceCodeId() : string | null
    {
        return $this->source_code_uuid;
    }

    public function addNewTask(array $data) : self
    {
        $this->recordThat(new NewTaskCreated($this->aggregateRootUuid(), $data));
        return $this;
    }

    public function reactivateTask(string $name) : self
    {
        if(array_key_exists($name, $this->deactivated_tasks))
        {
            $this->recordThat(new TaskReactivated($this->aggregateRootUuid(), $name));
        }

        return $this;
    }

    public function deactivateTask(string $name) : self
    {
        if(array_key_exists($name, $this->tasks))
        {
            $this->recordThat(new TaskDeactivated($this->aggregateRootUuid(), $name));
        }

        return $this;
    }

    public function getTasks()
    {
        return $this->tasks;
    }
}
