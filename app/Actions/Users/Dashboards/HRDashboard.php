<?php

namespace App\Actions\Users\Dashboards;

use Lorisleiva\Actions\Concerns\AsAction;

class HRDashboard
{
    use AsAction;

    public function handle()
    {
        $candidates_hired = [
            'candidates' => []
        ];
        return [
            'type'    => 'div',
            'class'   => 'row',
            'content' => [ // widgets
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
                    'class'   => 'card bg-info text-white', // optional
                    'content'    => [
                        //'header' => 'Users in Dept', // optional
                        'body'   => '
                            <div class="h1 text-muted text-right mb-4"><i class="las la-user-ninja nav-icon"></i></div>
                            <div class="text-value">0</div><small class="text-muted text-uppercase font-weight-bold">Candidates Interviewed</small>
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
                            <div class="text-value">00</div><small class="text-muted text-uppercase font-weight-bold">Candidates Hired.</small>
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
                        'header' => 'Paperwork Needed for New Hires', // optional
                        'body'   => view('card-bodies.hr-candidates-hired', $candidates_hired)->render(),
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-sm-12 col-md-6'], // optional
                    'class'   => 'card bg-light text-dark', // optional
                    'content'    => [
                        'header' => 'Message a User', // optional
                        'body'   => view('card-bodies.hr-message-a-user', $candidates_hired)->render(),
                    ]
                ],
            ]
        ];
    }
}
