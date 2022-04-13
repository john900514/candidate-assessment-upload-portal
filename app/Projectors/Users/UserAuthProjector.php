<?php

namespace App\Projectors\Users;

use App\Models\User;
use App\Models\UserDetails;
use App\StorableEvents\Users\UserCreated;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\StorableEvents\Users\Activity\API\AccessTokenGranted;

class UserAuthProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        $user = User::create($event->details);
        $user->id = $event->user_id;
        $user->save();

        $employee = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'employee_status',
            'active' => 1
        ]);

        $employee->value  = 'employee';

        Bouncer::assign($event->role)->to($user);
    }

    public function onAccessTokenGranted(AccessTokenGranted $event)
    {
        $user = User::find($event->user);
        $user->tokens()->delete();
        $token = $user->createToken($user->email)->plainTextToken;
        $deet = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'api-token',
            'active' => 1
        ]);

        $deet->value = base64_encode($token);
        $deet->save();
    }
}
