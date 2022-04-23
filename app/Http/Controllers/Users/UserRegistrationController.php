<?php

namespace App\Http\Controllers\Users;

use App\Aggregates\Users\UserAggregate;
use App\Exceptions\Users\UserAuthException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRegistrationController extends Controller
{
    public function index()
    {
        backpack_auth()->logout();
        if(request()->has('session'))
        {
            $aggy = UserAggregate::retrieve(request()->get('session'));

            if(!$aggy->isUserVerified())
            {


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
                        'role' => $role,
                        'email' => $aggy->getEmail(),
                        'userId' => request()->get('session')
                    ];

                    backpack_auth()->logout();
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

    public function finish_registration()
    {
        $data = request()->all();

        if(request()->has('user_id'))
        {
            try {
                $user_data = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email']
                ];
                UserAggregate::retrieve($data['user_id'])
                    ->updateUser($user_data)
                    ->updatePassword(bcrypt($data['password']))
                    ->verifyUser(date('Y-m-d, H:i:s'))
                    ->persist();

                return response(true, 200);
            }
            catch(\DomainException $e)
            {
                return response($e->getMessage(), 500);
            }
        }
        else
        {
            return response('No.', 401);
        }
    }

    public function show_resume_uploader()
    {
        $aggy = UserAggregate::retrieve(backpack_user()->id);
        if($aggy->isApplicant() && (!$aggy->hasSubmittedResume()))
        {
            $data = [];
            return Inertia::render('Candidates/Registration/UploadResume', $data);
        }
        else
        {
            throw UserAuthException::accessDenied();
        }
    }

    public function upload_resume()
    {

    }
}
