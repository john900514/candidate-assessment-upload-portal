<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Symfony\Component\VarDumper\VarDumper;

class AbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = [
            'user' => [
                'view_employees'    => ['ADMIN', 'EXECUTIVE', 'PROJECT_MANAGER', 'DEPT_HEAD', 'DEV_LEAD', 'DEV', 'JUNIOR_DEV'],
                'view_candidates'   => ['ADMIN', 'EXECUTIVE', 'PROJECT_MANAGER', 'DEPT_HEAD', 'DEV_LEAD'],
                'create_employees'  => ['ADMIN', 'EXECUTIVE', 'PROJECT_MANAGER', 'DEPT_HEAD'],
                'create_candidates' => ['ADMIN', 'EXECUTIVE', 'PROJECT_MANAGER', 'DEPT_HEAD', 'DEV_LEAD'],
                'promote_candidates_to_employees' => ['EXECUTIVE', 'DEPT_HEAD'],
                'promote_employees' => ['EXECUTIVE', 'DEPT_HEAD'],
                'delete_users' => ['EXECUTIVE', 'DEPT_HEAD'],
                'edit_users' => ['EXECUTIVE', 'DEPT_HEAD'],
            ],
            'project' => [
                'create_projects' => ['ADMIN', 'EXECUTIVE', 'PROJECT_MANAGER', 'DEPT_HEAD'],
            ],
            'team' => [
                'create_teams' => ['ADMIN','DEPT_HEAD', 'PROJECT_MANAGER'],
                'add_users_to_teams' => ['ADMIN', 'PROJECT_MANAGER', 'DEPT_HEAD', 'DEV_LEAD']
            ],
            'job_position' => [
                'create_job_positions' => ['ADMIN', 'EXECUTIVE', 'DEPT_HEAD'],
                'create_assessments' => ['DEPT_HEAD', 'DEV_LEAD'],
                'approve_assessments' => ['DEPT_HEAD'],
                'assign_assessment_to_job' => ['DEPT_HEAD'],
                'create_quizzes' => ['DEPT_HEAD', 'DEV_LEAD'],
                'approve_quizzes' => ['DEPT_HEAD', 'DEV_LEAD'],
                'assign_quiz_to_assessment' => ['DEPT_HEAD', 'DEV_LEAD'],
                'create_quiz_questions' => ['DEPT_HEAD', 'DEV_LEAD', 'SENIOR_DEV', 'DEV'],
                'approve_quiz_questions' => ['DEPT_HEAD', 'DEV_LEAD'],
                'create_file_upload_requirement' => ['DEPT_HEAD', 'DEV_LEAD'],  // This is for uploading a file for a question
                'create_code_download_requirement' => ['DEPT_HEAD', 'DEV_LEAD'], // This is downloading code for an assessment
            ]
        ];

        foreach($abilities as $genre => $ability_group)
        {
            VarDumper::dump("Adding {$genre} permissions");
            foreach($ability_group as $ability => $roles)
            {
                foreach($roles as $role)
                {
                    Bouncer::allow(strtolower($role))->to($ability);
                }
            }
        }
    }
}
