<?php

namespace App\Aggregates\Candidates\Assessments\Partials;

use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AssessmentQuizPartial extends AggregatePartial
{
    protected array $quiz_ids = [];
}
