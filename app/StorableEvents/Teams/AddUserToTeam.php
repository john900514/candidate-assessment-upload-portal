<?php

namespace App\StorableEvents\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AddUserToTeam extends ShouldBeStored
{
    public function __construct()
    {
    }
}
