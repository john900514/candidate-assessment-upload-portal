<?php

namespace App\Actions\Impersonation;

use App\Aggregates\Clients\ClientAggregate;
use App\Aggregates\Users\UserAggregate;
use Bouncer;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ImpersonateUser
{
    use AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {
        // ...
    }

    /*
    public function rules() : array
    {
        return [
            'victimId'  => 'bail|required|exists:users,id',
        ];
    }
    */

    public function handle(string $user_id)
    {
        $results = false;

        if(Bouncer::is(backpack_user())->a('dept_head'))
        {
            $other_user = User::find($user_id);

            if(!is_null($other_user))
            {
                if(!is_null($other_user->email_verified_at))
                {
                    $me = backpack_user();
                    backpack_user()->impersonate($other_user);
                    session()->put('impersonation-mode', $me->id);
                    backpack_auth()->login(auth()->user());
                    $results = true;
                }
            }
        }

        return $results;
    }

    public function htmlResponse($result)
    {
        if($result)
        {
            \Alert::info('You are now in impersonation mode! Disable it in the name dropdown or the bottom of the screen!')->flash();
            return redirect()->route('backpack.dashboard');
        }

        \Alert::error('Error. Impersonation mode not active.')->flash();
        return redirect()->back();

    }
}
