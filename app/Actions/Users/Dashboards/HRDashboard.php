<?php

namespace App\Actions\Users\Dashboards;

use Lorisleiva\Actions\Concerns\AsAction;

class HRDashboard
{
    use AsAction;

    public function handle()
    {
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
                            <div class="text-value">87.500</div><small class="text-muted text-uppercase font-weight-bold">Active Candidates</small>
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
                            <div class="text-value">87.500</div><small class="text-muted text-uppercase font-weight-bold">Active Applied</small>
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
                            <div class="text-value">87.500</div><small class="text-muted text-uppercase font-weight-bold">Candidates Interviewed</small>
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
                            <div class="text-value">87.500</div><small class="text-muted text-uppercase font-weight-bold">Candidates Hired.</small>
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
                        'body'   => '<div style="padding:63% 0 0 0; position:relative;"><iframe src="https://app.databox.com/datawall/4842547e37f5ebd2712758d7dc51a7560611c1b80?i" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
                    ]
                ],
                [
                    'type'       => 'card',
                    'wrapper' => ['class' => 'col-sm-12 col-md-6'], // optional
                    'class'   => 'card bg-light text-dark', // optional
                    'content'    => [
                        'header' => 'Message a User', // optional
                        'body'   => '<div style="padding:63% 0 0 0; position:relative;"><iframe src="https://app.databox.com/datawall/4842547e37f5ebd2712758d7dc51a7560611c1b80?i" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
                    ]
                ],
            ]
        ];
    }
}
