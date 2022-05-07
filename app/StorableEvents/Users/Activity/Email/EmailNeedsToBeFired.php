<?php

namespace App\StorableEvents\Users\Activity\Email;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EmailNeedsToBeFired extends ShouldBeStored
{
    public function __construct(public string $user_id,
                                public string $mail_class,
                                public array $details,
                                public string $date)
    {
    }
}
