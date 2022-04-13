<?php

namespace App\Aggregates\Clients;

use App\Exceptions\Clients\ClientException;
use App\StorableEvents\Clients\ClientCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ClientAggregate extends AggregateRoot
{
    protected string|null $client_name = null;

    public function applyClientCreated(ClientCreated $event)
    {
        $this->client_name = $event->name;
    }

    public function createClient(string $name): self
    {
        if(!is_null($this->client_name))
        {
            throw ClientException::clientAlreadyExists($name);
        }

        $this->recordThat(new ClientCreated($this->uuid(), $name));
        return $this;
    }
}
