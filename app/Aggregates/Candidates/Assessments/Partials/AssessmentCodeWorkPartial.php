<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use App\Exceptions\Candidates\AssessmentException;
use App\StorableEvents\Candidates\Assessments\SourceCodeFileAddedToAssessment;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AssessmentCodeWorkPartial extends AggregatePartial
{
    protected string|null $source_code_uuid = null;

    public function applySourceCodeFileAddedToAssessment(SourceCodeFileAddedToAssessment $event)
    {
        $this->source_code_uuid = $event->source_id;
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

}
