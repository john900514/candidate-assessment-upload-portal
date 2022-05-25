<?php

namespace App\Http\Controllers\Users;

use App\Aggregates\Users\UserAggregate;
use App\Exceptions\Users\UserAuthException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                        'firstName' => $aggy->getFirstName(),
                        'lastName' => $aggy->getLastName(),
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
        $user = backpack_user();
        $aggy = UserAggregate::retrieve($user->id);
        if($aggy->isApplicant() && (!$aggy->hasSubmittedResume()))
        {
            $data = [
                'userId' => $user->id
            ];
            return Inertia::render('Candidates/Registration/UploadResume', $data);
        }
        else
        {
            throw UserAuthException::accessDenied();
        }
    }

    public function upload_resume()
    {
        $results = 'Missing File';
        $code = 500;

        if(request()->has('file'))
        {
            $user = backpack_user();
            $file = request()->file('file');
            $folder = 'candidate_assessment/users/'.$user->id.'/resumes';
            $name = $file->getClientOriginalName();
            // Upload the file to the user's resume folder
            $path =  Storage::disk('s3')->putFileAs($folder, $file, $name);

            try {
                UserAggregate::retrieve($user->id)
                    ->submitResume($path)->persist();

                $code = 200;
                $results = true;
            }
            catch (\Exception $e)
            {
                $results = $e->getMessage();
            }

            /**
             * STEPS
             * 1.
             * 2. Use the user Aggregate and log resume uploaded.
             */
        }

        return response($results, $code);
    }

    public function verify_employee()
    {
        $user = backpack_user();
        $aggy = UserAggregate::retrieve($user->id);

        if($aggy->isEmployee() && (is_null($aggy->getVerificationDate())))
        {
            $data = [
                'userId' => $user->id,
                'user' => $user->toArray()
            ];
            return Inertia::render('Employees/Registration/VerifyAccount', $data);
        }
        else
        {
            throw UserAuthException::accessDenied();
        }
    }

    public function update_password_and_verify_employee()
    {
        $results = 'Bad Request';
        $code = 500;

        try {
            UserAggregate::retrieve(request()->get('id'))
                ->updatePassword(bcrypt(request()->get('password')))
                ->verifyUser(date('Y-m-d H:i:s'))
                ->persist();

            $results = true;
            $code = 200;
        }
        catch(\Exception $e)
        {
            $results = $e->getMessage();
        }

        return response($results, $code);
    }
}
