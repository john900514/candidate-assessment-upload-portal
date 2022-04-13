<?php

namespace Database\Seeders;

use App\Aggregates\Projects\ProjectAggregate;
use App\Models\Clients\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\VarDumper\VarDumper;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            'AnchorCMS' => [
                'client' => Client::getClientID('Cape & Bay'),
            ],
            'Candidate Assessment' => [
                'client' => Client::getClientID('Cape & Bay'),
            ],
            'GymRevenue Prototype CRM' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'GymRevenue Prototype API' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'GymRevenue Prototype CMS' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'GymRevenue CRM V1' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'GymRevenue API V1' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'GymRevenue CMS V1' => [
                'client' => Client::getClientID('GymRevenue'),
            ],
            'TruFit Athletic Clubs V4 Mainsite' => [
                'client' => Client::getClientID('TruFit Athletic Clubs'),
            ],
            'TruFit Athletic Clubs V4 Mobile App' => [
                'client' => Client::getClientID('TruFit Athletic Clubs'),
            ],
            'TruFit Athletic Clubs V4 Mobile App API' => [
                'client' => Client::getClientID('TruFit Athletic Clubs'),
            ],
            'TruFit Athletic Clubs V4 Reporting Process' => [
                'client' => Client::getClientID('TruFit Athletic Clubs'),
            ],
            'YouFit Health Clubs V2 Reporting Process' => [
                'client' => Client::getClientID('YouFit Health Clubs'),
            ],
            'Shae Hair Shopify' => [
                'client' => Client::getClientID('Shae Hair'),
            ],
            'Lave Shopify' => [
                'client' => Client::getClientID('TruFit Athletic Clubs'),
            ],
            'Syrian Forum V2' => [
                'client' => Client::getClientID('Syrian Forum USA'),
            ],
        ];

        foreach ($projects as $name => $project_info)
        {
            VarDumper::dump("Adding Project {$name}");
            $id = Uuid::uuid4()->toString();
            ProjectAggregate::retrieve($id)
                ->createProject($name, $project_info['client'])
                ->persist();
        }
    }
}
