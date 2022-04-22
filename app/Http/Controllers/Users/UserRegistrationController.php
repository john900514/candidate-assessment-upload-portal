<?php

namespace App\Http\Controllers\Users;

use App\Aggregates\Users\UserAggregate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRegistrationController extends Controller
{
    public function index()
    {
        if(request()->has('session'))
        {
            $aggy = UserAggregate::retrieve(request()->get('session'));

            if(!$aggy->isUserVerified())
            {

                backpack_auth()->logout();
                backpack_auth()->login(User::find(request()->get('session')));

                if($aggy->isEmployee())
                {
                    backpack_auth()->logout();
                    \Alert::add('info', 'That doesn\'t work right yet.')->flash();
                    return redirect(backpack_url('login'));
                }
                else
                {
                    $role = $aggy->getRole();
                    $payload = [
                        'role' => $role
                    ];

                    return Inertia::render('Candidates/Registration/CompleteRegistration', $payload);
                }

            }
            else
            {
                backpack_auth()->logout();
                \Alert::add('info', 'That doesn\'t work anymore.')->flash();
                return redirect(backpack_url('/login'));
            }
        }
        else
        {
            return redirect(backpack_url('/login'));
        }
    }
}
