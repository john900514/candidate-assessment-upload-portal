<?php

namespace Database\Seeders;

use App\Aggregates\Communication\MailingListAggregate;
use App\Enums\JobTypeEnum;
use App\Enums\UserRoleEnum;
use App\Models\Communication\MailingList;
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
        foreach(collect(JobTypeEnum::cases()) as $enum)
        {
            if(is_null(MailingList::whereConcentration($enum->value)->first()))
            {
                VarDumper::dump("Setting up making list for ".$enum->name);
                $uuid = Uuid::uuid4()->toString();
                MailingListAggregate::retrieve($uuid)
                    ->createMailingList($enum->name, $enum->value)
                    ->persist();
            }
            else
            {
                VarDumper::dump("Skipping mailing list ".$enum->name);
            }

        }
    }
}
