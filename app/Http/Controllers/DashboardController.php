<?php

namespace App\Http\Controllers;

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

        if(false)
        {

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
