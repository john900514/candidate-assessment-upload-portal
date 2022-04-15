<?php

namespace App\Actions\InboundAPI\Users\Auth;

use Lorisleiva\Actions\Concerns\AsAction;

class VerifyAccessToken
{
    use AsAction;

    public function handle()
    {
        return true;
    }

    public function jsonResponse()
    {
        return response([true], 200);
    }
}
