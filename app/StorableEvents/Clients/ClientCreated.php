<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientCreated extends ShouldBeStored
{
    public function __construct(public string $client_id, public string $name)
    {

    }
}
