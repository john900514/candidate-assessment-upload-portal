<?php

namespace App\StorableEvents\Assets\Files;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewSourceCodeFileCreated extends ShouldBeStored
{
    public function __construct(public string $file_id, public array $file, public array $source)
    {
    }
}
