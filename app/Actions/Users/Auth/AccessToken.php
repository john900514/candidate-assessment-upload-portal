<?php

namespace App\Actions\Users\Auth;

use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class AccessToken
{
    use AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {
        // ...
    }

    public function handle() : string | false
    {
        $user_id = backpack_user()->id;
        UserAggregate::retrieve($user_id)
            ->grantAccessToken()->persist();

        return UserAggregate::retrieve($user_id)->getAccessToken();
    }

    public function asController()
    {
        return $this->handle();
    }

    public function jsonResponse(string|false $result)
    {
        $results = ['success' => false];
        $code = 500;

        if($result)
        {
            $results = ['success' => true, 'token' => $result];
            $code = 200;
        }

        return response($results, $code);
    }

    public function htmlResponse(string|false $result)
    {
        if($result)
        {
            \Alert::add('success','Your new Access Token has been generated!')->flash();
        }
        else
        {
            \Alert::add('error','Access Token could not be generated. Please Try Again.')->flash();
        }

        return redirect()->route('backpack.account.info');
    }
}
