<?php

namespace App\Projectors\Candidates;

use App\Models\Candidates\Assessment;
use App\Models\Candidates\Tasks\AssessmentTask;
use App\StorableEvents\Candidates\Assessments\AssessmentUpdated;
use App\StorableEvents\Candidates\Assessments\NewAssessmentCreated;
use App\StorableEvents\Candidates\Assessments\Tasks\NewTaskCreated;
use App\StorableEvents\Candidates\Assessments\Tasks\TaskDeactivated;
use App\StorableEvents\Candidates\Assessments\Tasks\TaskReactivated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class AssessmentProjector extends Projector
{
    public function onNewAssessmentCreated(NewAssessmentCreated $event)
    {
        $assessment = Assessment::create($event->payload);
        $assessment->id = $event->assessment_id;
        $assessment->has_quizzes = $event->has_quizzes;
        $assessment->has_source_code = $event->has_source_code;
        $assessment->save();
    }

    public function onAssessmentUpdated(AssessmentUpdated $event)
    {
        $assessment = Assessment::find($event->assessment_id);
        $assessment->update($event->payload);
        $assessment->has_quizzes = $event->has_quizzes;
        $assessment->has_source_code = $event->has_source_code;
        $assessment->save();
    }

    public function onNewTaskCreated(NewTaskCreated $event)
    {
        AssessmentTask::create($event->task_data);
    }

    public function onTaskDeactivated(TaskDeactivated $event)
    {
        $task = AssessmentTask::whereAssessmentId($event->assessment_id)
            ->whereTaskName($event->name)->first();
        $task->update(['active' => 0]);
    }

    public function onTaskReactivated(TaskReactivated $event)
    {
        $task = AssessmentTask::whereAssessmentId($event->assessment_id)
            ->whereTaskName($event->name)->first();
        $task->update(['active' => 1]);
    }
}
