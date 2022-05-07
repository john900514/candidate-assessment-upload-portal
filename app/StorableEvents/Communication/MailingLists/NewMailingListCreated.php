<?php

namespace App\StorableEvents\Communication\MailingLists;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewMailingListCreated extends ShouldBeStored
{
    public function __construct(public string $list_id, public string $name, public string $concentration)
    {
    }
}
