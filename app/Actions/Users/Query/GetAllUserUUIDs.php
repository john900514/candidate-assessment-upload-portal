<?php

namespace App\Actions\Users\Query;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllUserUUIDs
{
    use AsAction;

    public function handle() : array
    {
        $results = [];
        $my_user_id = backpack_user()->id;
        $users = User::all();

        if(count($users) > 0)
        {
            foreach ($users as $user)
            {
                if($user->id != $my_user_id)
                {
                    $results[] = $user->id;
                }
            }
        }

        return $results;
    }
}
