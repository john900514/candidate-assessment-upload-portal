<?php

namespace Database\Seeders;

use App\Aggregates\Clients\ClientAggregate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            'Cape & Bay',
            'GymRevenue',
            'TruFit Athletic Clubs',
            'YouFit Health Clubs',
            'Lave',
            'Shae Hair',
            'Syrian Forum USA',
        ];

        foreach ($clients as $client)
        {
            $id = Uuid::uuid4()->toString();
            ClientAggregate::retrieve($id)
                ->createClient($client)
                ->persist();
        }
    }
}
