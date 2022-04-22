<?php

namespace App\Reactors\Users\Auth;

use App\Mail\Users\Candidates\WelcomeNewCandidateMail;
use App\Mail\Users\Employees\WelcomeNewEmployeeMail;
use App\Models\User;
use App\StorableEvents\Users\Activity\Email\UserSentWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserAuthReactor extends Reactor implements ShouldQueue
{
    public function onUserSentWelcomeEmail(UserSentWelcomeEmail $event)
    {
        $user = User::find($event->user_id);
        // Check the employee status
        if($event->employee_status == 'employee')
        {
            Mail::to($user->email)->send(new WelcomeNewEmployeeMail($user->id));
        }
        else
        {
            Mail::to($user->email)->send(new WelcomeNewCandidateMail($user->id));
        }
    }
}
