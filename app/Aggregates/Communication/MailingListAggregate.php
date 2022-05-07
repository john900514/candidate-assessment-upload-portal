<?php

namespace App\Aggregates\Communication;

use App\Exceptions\Communication\MailingListException;
use App\StorableEvents\Communication\MailingLists\NewMailingListCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class MailingListAggregate extends AggregateRoot
{
    protected array $users = [];
    protected string|null $concentration = null;
    protected string|null $name = null;

    public function applyNewMailingListCreated(NewMailingListCreated $event)
    {
        $this->name = $event->name;
        $this->concentration = $event->concentration;
    }
    public function createMailingList(string $name, string $concentration) : self
    {
        if(!is_null($this->name))
        {
            throw MailingListException::mailingListAlreadyCreated();
        }

        $this->recordThat(new NewMailingListCreated($this->uuid(), $name, $concentration));

        return $this;
    }
}
