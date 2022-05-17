<?php

namespace Database\Seeders;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Projects\ProjectAggregate;
use App\Enums\CompanyDepartmentsEnum;
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
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FULLSTACK,
                'awarded_role' => UserRoleEnum::SENIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FS_CANDIDATE,
                    UserRoleEnum::BE_CANDIDATE,
                ],
            ],
            [
                'position' => 'Senior Frontend Developer',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::SENIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Frontend Web Developer I',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Backend Web Developer I',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::BACKEND,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::BE_CANDIDATE
                ],
            ],
            [
                'position' => 'Full Stack Web Developer I',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FULLSTACK,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::FS_CANDIDATE
                ],
            ],
            [
                'position' => 'Junior Frontend Web Developer',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FRONTEND,
                'awarded_role' => UserRoleEnum::JUNIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE
                ],
            ],
            [
                'position' => 'Junior Backend Web Developer',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::BACKEND,
                'awarded_role' => UserRoleEnum::JUNIOR_DEV,
                'candidates' => [
                    UserRoleEnum::BE_CANDIDATE
                ],
            ],
            [
                'position' => 'Junior FullStack Web Developer',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::FULLSTACK,
                'awarded_role' => UserRoleEnum::JUNIOR_DEV,
                'candidates' => [
                    UserRoleEnum::FS_CANDIDATE
                ],
            ],
            [
                'position' => 'JavaScript/Mobile Developer',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::SPECIALIST,
                'awarded_role' => UserRoleEnum::DEV,
                'candidates' => [
                    UserRoleEnum::FE_CANDIDATE,
                    UserRoleEnum::BE_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE,
                ],
            ],
            [
                'position' => 'Senior Data Analyst',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
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
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::ARCHITECT,
                'awarded_role' => UserRoleEnum::PROJECT_MANAGER,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE
                ],
            ],
            [
                'position' => 'Dev Team Lead',
                'department' => CompanyDepartmentsEnum::ENGINEERING,
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
                'department' => CompanyDepartmentsEnum::ENGINEERING,
                'concentration' =>  JobTypeEnum::SCRUM,
                'awarded_role' => UserRoleEnum::PROJECT_MANAGER,
                'candidates' => [
                    UserRoleEnum::MGNT_CANDIDATE,
                    UserRoleEnum::FS_CANDIDATE,
                ],
            ],
            // @todo - add job positions for other departments here
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
