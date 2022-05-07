<?php

namespace App\Reactors\Users;

use App\Aggregates\Users\UserAggregate;
use App\StorableEvents\Users\Activity\Email\EmailNeedsToBeFired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserActivityReactor extends Reactor implements ShouldQueue
{
    public function onEmailNeedsToBeFired(EmailNeedsToBeFired $event)
    {
        $user_aggy = UserAggregate::retrieve($event->user_id);
        Mail::to($user_aggy->getEmail())->send(new $event->mail_class($event->user_id, $event->details, $event->date));
    }
}
