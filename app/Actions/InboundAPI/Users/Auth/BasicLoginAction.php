<?php

namespace App\Actions\InboundAPI\Users\Auth;

use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class BasicLoginAction
{
    use AsAction;

    public function handle()
    {
        backpack_auth()->login(auth()->user());
        $user = backpack_user();

        if(!is_null($user))
        {
            $aggy = UserAggregate::retrieve($user->id);
            if($token = $aggy->getAccessToken())
            {
                $token = ['token' => $token];
            }
            else
            {
                $aggy->grantAccessToken()->persist();
                $token = ['token' => $aggy = UserAggregate::retrieve($user->id)->getAccessToken()];
            }

            return $token;
        }
        else
        {
            return 'Invalid Credentials or you Need you login from the API.';
        }
    }

    public function asController()
    {
        return $this->handle();
    }

    public function jsonResponse(string|false|array $result)
    {
        $results = false;
        $code = 500;

        if(is_string($result))
        {
            $results = ['error' => $result];
            $code = 400;
        }
        elseif(is_array($result))
        {
            $results = ['token' => $result['token']];
            $code = 200;
        }

        return response($results, $code);
    }
}
