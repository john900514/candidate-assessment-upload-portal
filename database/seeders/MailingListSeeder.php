<?php

namespace Database\Seeders;

use App\Aggregates\Communication\MailingListAggregate;
use App\Enums\JobTypeEnum;
use App\Enums\UserRoleEnum;
use App\Models\Communication\MailingList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\VarDumper\VarDumper;

class MailingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VarDumper::dump('Adding uncreated Mailing Lists');
        foreach(collect(JobTypeEnum::cases()) as $enum)
        {
            if(is_null($list_model = MailingList::whereConcentration($enum->value)->first()))
            {
                VarDumper::dump("Setting up making list for ".$enum->name);
                $uuid = Uuid::uuid4()->toString();
                MailingListAggregate::retrieve($uuid)
                    ->createMailingList($enum->name, $enum->value)
                    ->persist();
            }
            else
            {
                $uuid = $list_model->id;
                VarDumper::dump("Skipping mailing list ".$enum->name);
            }

            switch(env('APP_ENV'))
            {
                case 'production':
                    $users = [
                        'angel@capeandbay.com',
                        'philip@capeandbay.com'
                    ];
                    break;

                default:
                    $users = [
                        'angel@capeandbay.com'
                    ];
            }

            $list = MailingListAggregate::retrieve($uuid);
            foreach ($users as $email)
            {
                $user_record = User::whereEmail($email)->first();

                if((!is_null($user_record)) && (!$list->isUserInList($user_record->id)))
                {
                    $list->addUserToMailingList($user_record->id, $email);
                }
            }

            $list->persist();

        }
    }
}
