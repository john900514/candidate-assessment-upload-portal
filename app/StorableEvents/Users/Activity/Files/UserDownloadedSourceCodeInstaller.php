<?php

namespace App\StorableEvents\Users\Activity\Files;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserDownloadedSourceCodeInstaller extends ShouldBeStored
{
    public function __construct(public string $user_id, public string $date)
    {
    }
}
