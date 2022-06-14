<?php

namespace App\Actions\Impersonation;

use App\Aggregates\Clients\ClientAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class StopImpersonatingUser
{
    use AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {
        // ...
    }

    public function handle()
    {
        $results = false;

        if(session()->has('impersonation-mode'))
        {
            $original_user = User::find(session()->get('impersonation-mode'));
            backpack_user()->leaveImpersonation();
            backpack_auth()->login($original_user);
            $results = true;
        }

        return $results;
    }


    public function htmlResponse($result)
    {
        if($result)
        {
            \Alert::info('Welcome back to the real world!')->flash();
            return redirect()->route('backpack.dashboard');
        }

        \Alert::error('Error. Impersonation mode not active.')->flash();
        return redirect()->back();

    }
}
