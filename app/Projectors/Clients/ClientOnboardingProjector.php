<?php

namespace App\Projectors\Clients;

use App\Models\Clients\Client;
use App\StorableEvents\Clients\ClientCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ClientOnboardingProjector extends Projector
{
    public function onClientCreated(ClientCreated $event)
    {
        $client = Client::create(['name' => $event->name]);
        $client->id = $event->client_id;
        $client->save();
    }
}
