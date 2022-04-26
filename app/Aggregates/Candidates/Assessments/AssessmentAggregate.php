<?php

namespace App\Aggregates\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\Partials\AssessmentCodeWorkPartial;
use App\Aggregates\Candidates\Assessments\Partials\AssessmentQuizPartial;
use App\Exceptions\Candidates\AssessmentException;
use App\Models\Projects\Project;
use App\StorableEvents\Candidates\Assessments\AssessmentUpdated;
use App\StorableEvents\Candidates\Assessments\NewAssessmentCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class AssessmentAggregate extends AggregateRoot
{
    protected string|null $name = null;
    protected int|null $concentration = null;
    protected bool $live = false;
    protected bool $has_quizzes = false;
    protected bool $has_source_code = false;
    protected string|null $approved_by_user = null;
    protected array $history = [];

    protected AssessmentQuizPartial $quizzes;
    protected AssessmentCodeWorkPartial $code_work;

    public function __construct()
    {
        $this->quizzes = new AssessmentQuizPartial($this);
        $this->code_work = new AssessmentCodeWorkPartial($this);
    }

    public function applyNewAssessmentCreated(NewAssessmentCreated $event)
    {
        $this->name = $event->payload['assessment_name'];
        $this->concentration = $event->payload['concentration'];
        $this->has_quizzes = $event->has_quizzes;
        $this->has_source_code = $event->has_source_code;
    }

    public function applyAssessmentUpdated(AssessmentUpdated $event)
    {
        $this->name = $event->payload['assessment_name'];
        $this->concentration = $event->payload['concentration'];
        $this->has_quizzes = $event->has_quizzes;
        $this->has_source_code = $event->has_source_code;

        $this->history[] = [
            'user' => $event->updated_by_user_id,
            'data' => $event->payload
        ];

        if($event->payload['active'] && (!$this->live))
        {
            $this->approved_by_user = $event->updated_by_user_id;
            $this->live = true;
        }

    }

    public function createNewAssessment(array $payload, bool $has_quizzes, bool $has_source_code) : self
    {
        if(!is_null($this->name))
        {
            throw AssessmentException::assessmentAlreadyCreated();
        }

        $this->recordThat(new NewAssessmentCreated($this->uuid(), $payload, $has_quizzes, $has_source_code));
        return $this;
    }

    public function updateAssessment(array $payload, bool $has_quizzes, bool $has_source_code, string $user) : self
    {
        $this->recordThat(new AssessmentUpdated($this->uuid(), $payload, $has_quizzes, $has_source_code, $user));
        return $this;
    }

    public function addSourceCodeRecord(string $source_id) : self
    {
        if(!$this->hasCodeWork())
        {
            throw AssessmentException::cannotAddSourceCode();
        }

        $this->code_work->addSourceCodeRecord($source_id);
        return $this;
    }

    public function addNewTask(array $data) : self
    {
        $this->code_work->addNewTask($data);
        return $this;
    }

    public function deactivateTask(string $name) : self
    {
        $this->code_work->deactivateTask($name);
        return $this;
    }

    public function reactivateTask(string $name) : self
    {
        $this->code_work->deactivateTask($name);
        return $this;
    }

    public function hasQuizzes() : bool
    {
        return $this->has_quizzes;
    }

    public function hasCodeWork() : bool
    {
        return $this->has_source_code;
    }

    public function getCodeWorkId() : string | null
    {
        return $this->code_work->getSourceCodeId();
    }

    public function getTasks() : array
    {
        return $this->code_work->getTasks();
    }

    public function getName() : string|null
    {
        return $this->name;
    }
}
