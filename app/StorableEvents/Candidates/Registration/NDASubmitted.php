<?php

namespace App\StorableEvents\Candidates\Registration;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NDASubmitted extends ShouldBeStored
{
    public function __construct(public string $user_id, public array $details)
    {
    }
}
