<?php

namespace Database\Seeders;

use App\Aggregates\Teams\TeamAggregate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Projects\Project;
use Ramsey\Uuid\Uuid;
use Symfony\Component\VarDumper\VarDumper;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            'GymRevenue Prototype CMS & API' => [
                'projects' => [
                    'GymRevenue Prototype CRM',
                    'GymRevenue Prototype API',
                ]
            ],
            'TruFit Athletic Clubs V4' => [
                'projects' => [
                    'TruFit Athletic Clubs V4 Mainsite',
                    'TruFit Athletic Clubs V4 Reporting Process'
                ],
            ],
            'Candidate Assessment' => [
                'projects' => [
                    'Candidate Assessment'
                ]
            ],
            'Shae Hair' => [
                'projects' => [
                    'Shae Hair Shopify'
                ]
            ],
            'Lave' => [
                'projects' => [
                    'Lave Shopify'
                ]
            ],
            'Syrian Forum' => [
                'projects' => [
                    'Syrian Forum V2'
                ]
            ],
        ];

        foreach ($teams as $team_name => $details)
        {
            $id = Uuid::uuid4()->toString();
            $aggy = TeamAggregate::retrieve($id)
                ->createTeam($team_name);
            VarDumper::dump("Creating {$team_name}");
            foreach ($details as $detail => $value)
            {
                switch ($detail)
                {
                    case 'projects':
                        foreach ($value as $project_name)
                        {
                            $project = Project::whereProjectName($project_name)->first();
                            $aggy = $aggy->addProjectToTeam($project->id);
                        }
                        break;
                }
            }

            $aggy->persist();
        }
    }
}
