<?php

namespace App\Http\Controllers;

use App\Actions\Candidate\ToDos\GetToDoList;
use App\Aggregates\Users\UserAggregate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected array $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    public function index()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        $aggy = UserAggregate::retrieve(backpack_user()->id);

        if($aggy->isApplicant())
        {
            if($aggy->hasSubmittedResume())
            {
                $todo_list_data = GetToDoList::run(backpack_user()->id);

                $this->data['widgets'] = [
                    'before_content' => [
                        [
                            'type'        => 'card',
                            'content' => [
                                'header' => 'Download the Installer',
                                'body' => view('card-bodies.download-the-installer')->render()
                            ],
                            'class' => 'bg-info',
                            'wrapper' => ['class' => 'col-6']

                        ],
                        [
                            'type'        => 'card',
                            'content' => [
                                'header' => 'Your ToDo List',
                                'body' => view('card-bodies.applicants-todo-list', ['list' => $todo_list_data])->render()
                            ],
                            'class' => 'bg-secondary',
                            'wrapper' => ['class' => 'col-6']

                        ],
                        [
                            'type'        => 'card',
                            'content' => [
                                'header' => $aggy->getFirstName()."'s Open Positions",
                                'body' => view('card-bodies.applicants-open-positions')->render()
                            ],
                            'class' => 'bg-success',
                            'wrapper' => ['class' => 'col-6 pt-4']

                        ]
                    ]
                ];
            }
            else
            {
                // otherwise, redirect to the resume upload page.
                return redirect('/portal/registration/upload-resume');
            }
        }
        else
        {
            // The default Empty State
            $this->data['widgets'] = [
                'before_content' => [
                    [
                        'type'        => 'jumbotron',
                        'heading'     => 'Hi!',
                        'content'     => "There's nothing here for you right now. Come back later!",
                        'button_link' => backpack_url('logout'),
                        'button_text' => 'Ok Bye.',
                    ]
                ]
            ];
        }

        return view(backpack_view('dashboard'), $this->data);
    }
}
