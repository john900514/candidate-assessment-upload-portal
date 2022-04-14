<?php

namespace App\Projectors\Assets;

use App\Models\Assets\SourceCodeUpload;
use App\Models\Assets\UploadedFile;
use App\StorableEvents\Assets\Files\NewSourceCodeFileCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class FileUploadProjector extends Projector
{
    public function onNewSourceCodeFileCreated(NewSourceCodeFileCreated $event)
    {
        $file_record = UploadedFile::create($event->file);
        $file_record->id = $event->file_id;
        $file_record->save();

        $source_record = SourceCodeUpload::create($event->source);
        $source_record->id = $event->source['id'];
        $source_record->save();
    }
}
