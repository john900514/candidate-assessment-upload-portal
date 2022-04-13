<?php

namespace Database\Seeders;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Projects\ProjectAggregate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\JobTypeEnum;
use App\Enums\UserRoleEnum;
use Ramsey\Uuid\Uuid;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_open_positions = [
            [
                'position' => 'Senior FullStack Developer',
                'concentration' =>  JobTypeEnum::FULLSTACK,
                'awarded_role' => UserRoleEnum::SENIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FS_CANDIDATE
                ],
            ],
            [
                'position' => 'Senior Frontend Developer',
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::SENIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Web Developer (FE)',
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Web Developer (BE)',
                'concentration' =>  JobTypeEnum::BACKEND,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::BE_CANDIDATE
                ],
            ],
            [
                'position' => 'Junior Web Developer (FE)',
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::JUNIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Junior Web Developer (BE)',
                'concentration' =>  JobTypeEnum::BACKEND,
                'awarded_role' => UserRoleEnum::JUNIOR_DEV,
                'candidates' => [
                    UserRoleEnum::BE_CANDIDATE
                ],
            ],
            [
                'position' => 'JavaScript/Mobile Developer',
                'concentration' =>  JobTypeEnum::SPECIALIST,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Senior Data Analyst',
                'concentration' =>  JobTypeEnum::SPECIALIST,
                'awarded_role' => UserRoleEnum::SENIOR_DEV,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE,
                    UserRoleEnum::BE_CANDIDATE
                ],
            ],
            [
                'position' => 'Software Architect',
                'concentration' =>  JobTypeEnum::ARCHITECT,
                'awarded_role' => UserRoleEnum::PROJECT_MANAGER,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE
                ],
            ],
            [
                'position' => 'Dev Team Lead',
                'concentration' =>  JobTypeEnum::TEAM_LEAD,
                'awarded_role' => UserRoleEnum::DEV_LEAD,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FE_CANDIDATE,
                    UserRoleEnum::BE_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE,
                ],
            ],
            [
                'position' => 'SCRUM Master',
                'concentration' =>  JobTypeEnum::SCRUM,
                'awarded_role' => UserRoleEnum::PROJECT_MANAGER,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE,
                ],
            ],
        ];

        foreach($default_open_positions as $open_position)
        {
            $id = Uuid::uuid4()->toString();
            $candidates = $open_position['candidates'];
            $aggy = JobPositionAggregate::retrieve($id)
                ->createJobPosition($open_position);

            foreach ($candidates as $candidate)
            {
                $aggy = $aggy->addQualifiedRole($candidate->value);
            }

            $aggy->persist();
        }
    }
}
