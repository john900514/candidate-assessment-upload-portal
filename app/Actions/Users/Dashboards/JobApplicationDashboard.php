<?php

namespace App\Actions\Users\Dashboards;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class JobApplicationDashboard
{
    use AsAction;

    public function handle(JobPositionAggregate $job_aggy, UserAggregate $user_aggy)
    {
        $user_data = $user_aggy->getAssessmentStatus($job_aggy->uuid());
        $no_assessments_completed = 0;
        $no_quizzes = 0;
        $no_quizzes_completed = 0;
        $no_tasks = 0;
        $no_tasks_completed = 0;
        foreach ($user_data['assessments'] as $assessment_id => $udata)
        {
            if(($udata['status'] == 'Completed') ||$udata['status'] == 'Complete')
            {
                $no_assessments_completed++;
            }

            $ass_aggy = AssessmentAggregate::retrieve($assessment_id);
            $no_quizzes += count($ass_aggy->getQuizzes());
            $no_tasks += count($ass_aggy->getTasks());
            foreach($udata['task_statuses'] as $task_name => $task_status)
            {
                if(($task_status['status'] == 'Completed') ||$task_status['status'] == 'Complete')
                {
                    $no_tasks_completed++;
                }
            }
        }

        $pending_app_status = $job_aggy->getPendingCandidates()[$user_aggy->uuid()] ?? [];
        $applied_date = $pending_app_status['date']->subHours(4);
        $month = $applied_date->shortEnglishMonth;
        $day = $applied_date->day;
        $year = $applied_date->year;
        $hour = date('h', $applied_date->getTimestamp());
        $minute = $applied_date->minute < 10 ? '0'.$applied_date->minute : $applied_date->minute;
        $meridian = date('A', $applied_date->getTimestamp());
        // Jan 7, 2022 @ 6:52PM
        // and 5 days ago
        $apply_date = "{$month} {$day}, {$year} @ {$hour}:{$minute}{$meridian}";

        $w1_pct = count($job_aggy->getAssessments()) == 0 ? 0 : ($no_assessments_completed /count($job_aggy->getAssessments())) * 100;
        $w2_pct = $no_quizzes == 0 ? 0 : ($no_quizzes_completed /$no_quizzes) * 100;
        $w3_pct = $no_tasks == 0 ? 0 : ($no_tasks_completed /$no_tasks) * 100;


        return [
            'type'    => 'div',
            'class'   => 'row',
            'content' => [ // widgets
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-info text-white', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-ninja nav-icon"></i></div>
                            <div class="text-value">'.$no_assessments_completed.'/'.count($job_aggy->getAssessments()).'</div><small class="text-muted text-uppercase font-weight-bold">Assessments Completed</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: '.$w1_pct.'%" aria-valuenow="'.$w1_pct.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-danger text-white', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-ninja nav-icon"></i></div>
                            <div class="text-value">'.$no_quizzes_completed.'/'.$no_quizzes.'</div><small class="text-muted text-uppercase font-weight-bold"># of Quizzes Completed</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: '.$w2_pct.'%" aria-valuenow="'.$w2_pct.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-warning text-white', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-ninja nav-icon"></i></div>
                            <div class="text-value">'.$no_tasks_completed.'/'.$no_tasks.'</div><small class="text-muted text-uppercase font-weight-bold"># of Tasks Completed</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: '.$w3_pct.'%" aria-valuenow="'.$w3_pct.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-success text-white', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-ninja nav-icon"></i></div>
                            <div class="text-value"><small>'.$apply_date.'</small></div><small class="text-muted text-uppercase font-weight-bold">Date Submitted</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
            ]
        ];
    }
}
