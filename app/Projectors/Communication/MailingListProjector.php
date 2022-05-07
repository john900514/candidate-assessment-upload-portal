<?php

namespace App\Projectors\Communication;

use App\Models\Communication\MailingList;
use App\StorableEvents\Communication\MailingLists\NewMailingListCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MailingListProjector extends Projector
{
    public function onNewMailingListCreated(NewMailingListCreated $event)
    {
        $record = MailingList::create([
            'list_name' => $event->name,
            'concentration' => $event->concentration
        ]);
        $record->update(['id' => $event->list_id]);
    }
}
