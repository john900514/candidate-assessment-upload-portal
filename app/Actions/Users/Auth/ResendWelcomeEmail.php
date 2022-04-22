<?php

namespace App\Actions\Users\Auth;

use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class ResendWelcomeEmail
{
    use AsAction;

    public function handle(string $user_id) : bool
    {
        $results = false;

        $aggy = UserAggregate::retrieve($user_id);

        if(!$aggy->isUserVerified())
        {
            $status = $aggy->isEmployee() ? 'employee' : 'applicant';
            $aggy->sendWelcomeEmail($status)->persist();
            $results = true;
        }


        return $results;
    }

    public function asController(string $user_id)
    {
        if($this->handle($user_id))
        {
            \Alert::add('success', 'Email Resent!')->flash();
        }
        else
        {
            \Alert::add('error', 'Email Resend Did not work. Ooops.')->flash();
        }

        return redirect()->back();
    }
}
