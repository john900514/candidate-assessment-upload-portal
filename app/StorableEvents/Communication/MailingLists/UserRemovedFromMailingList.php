<?php

namespace App\StorableEvents\Communication\MailingLists;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserRemovedFromMailingList extends ShouldBeStored
{
    public function __construct(public string $list_id, public string $user_id, public string $email)
    {
    }
}
