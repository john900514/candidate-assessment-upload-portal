<?php

namespace Database\Seeders;

use App\Aggregates\Users\UserAggregate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\VarDumper\VarDumper;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dev_dept = [
            [
                'first_name' => 'Developer',
                'last_name' => 'Account',
                'name' => 'dev',
                'email' => 'developers@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'admin'
            ],
            [
                'first_name' => 'Angel',
                'last_name' => 'Gonzalez',
                'name' => 'angel',
                'email' => 'angel@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'dept_head',
                'dept' => 'ENGINEERING'
            ],
            [
                'first_name' => 'Philip',
                'last_name' => 'Krogel',
                'name' => 'philip',
                'email' => 'philip@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'dev_lead',
                'dept' => 'ENGINEERING'
            ],
            [
                'first_name' => 'Blair',
                'last_name' => 'Patterson',
                'name' => 'blair',
                'email' => 'blair@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'senior_dev',
                'dept' => 'ENGINEERING'
            ],
            [
                'first_name' => 'Sterling',
                'last_name' => 'Webb',
                'name' => 'sterling',
                'email' => 'sterling@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'senior_dev',
                'dept' => 'ENGINEERING'
            ],
            [
                'first_name' => 'Shivam',
                'last_name' => 'Shewa',
                'name' => 'shivam',
                'email' => 'shivam@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'project_manager',
                'dept' => 'ENGINEERING'
            ],
            [
                'first_name' => 'Amy',
                'last_name' => 'Howell',
                'name' => 'amy',
                'email' => 'amy@capeandbay.com',
                'password' => bcrypt('Hello123!'),
                'role' => 'dept_head',
                'dept' => 'HR'
            ]
        ];

        foreach($dev_dept as $dev)
        {
            VarDumper::dump($dev['name']);
            $role = $dev['role'];
            unset($dev['role']);
            $aggy = UserAggregate::retrieve(Uuid::uuid4()->toString())
                ->createUser($dev, $role);

            /* Uncomment if dev on local.
            if($role != 'applicant')
            {
                $aggy = $aggy->verifyUser(date('Y-m-d H:i:s'));
            }
            */

            $aggy->persist();
        }
    }
}
