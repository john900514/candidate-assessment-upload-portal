<?php

namespace App\Actions\Users\Dashboards;

use App\Aggregates\Users\UserAggregate;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DevLeadDashboard
{
    use AsAction;

    public function handle()
    {
        $my_aggy = backpack_user()->getAggregate();
        $users = User::all();
        $dept_users = [];
        foreach($users as $idx => $user)
        {
            if($user_aggy = $user->getAggregate())
            {
                if($user_aggy->isEmployee())
                {
                    if($user_aggy->getDepartment() == $my_aggy->getDepartment())
                    {
                        $dept_users[] = $user;
                    }
                }

            }
        }

        /**
         * STEPS
         * 1. Get Active Candidates
         * 2. Get Amt of Candidates Applied
         * 3. Get #2 more data for table
         */
        $dept_candidates_to_review = [
            'candidates' => []
        ];

        $employee_tickets = [];
        // @todo - change tickets table to empty

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
                            <div class="text-value">'.count($dept_users).'</div><small class="text-muted text-uppercase font-weight-bold">Dept. Users</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-info text-white', // optional
                    'content'    => [
                        //'header' => 'Assigned to You', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-tie"></i></div>
                            <div class="text-value">0</div><small class="text-muted text-uppercase font-weight-bold">Active Candidates</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',

                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-warning text-dark', // optional
                    'content'    => [
                        //'header' => 'Messages', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-tie"></i></div>
                            <div class="text-value">0</div><small class="text-muted text-uppercase font-weight-bold">Active Applied</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-6 col-md-3'], // optional
                    'class'   => 'card bg-warning text-dark', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-users"></i></div>
                            <div class="text-value">0/0</div><small class="text-muted text-uppercase font-weight-bold">Tickets Completed.</small>
                            <div class="progress progress-white progress-xs mt-3">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-sm-12 col-md-6'], // optional
                    'class'   => 'card bg-light text-dark', // optional
                    'content'    => [
                        'header' => 'Candidates to Review', // optional
                        'body'   => view('card-bodies.employee-candidates-to-review', $dept_candidates_to_review)->render(),
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-sm-12 col-md-6'], // optional
                    'class'   => 'card bg-light text-dark', // optional
                    'content'    => [
                        'header' => 'Tickets Assigned to You', // optional
                        'body'   => view('card-bodies.employee-tickets-assigned', $employee_tickets)->render(),
                    ]
                ],
            ]
        ];
    }
}
