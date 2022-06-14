<?php

namespace App\Actions\Users\Query;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllCandidateUUIDs
{
    use AsAction;

    public function handle()
    {
        $results = [];
        $my_user_id = backpack_user()->id;
        $users = User::with('employee_status')->get();

        if(count($users) > 0)
        {
            foreach ($users as $user)
            {
                if($user->employee_status->value != 'employee')
                {
                    if($user->id != $my_user_id)
                    {
                        $results[] = $user->id;
                    }
                }
            }
        }

        return $results;
    }
}
