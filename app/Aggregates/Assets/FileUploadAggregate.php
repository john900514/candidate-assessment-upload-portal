<?php

namespace App\Aggregates\Assets;

use App\Exceptions\Assets\UploadedFileException;
use App\Models\Assets\SourceCodeUpload;
use App\StorableEvents\Assets\Files\NewSourceCodeFileCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class FileUploadAggregate extends AggregateRoot
{
    protected string|null $name = null;
    protected string|null $path = null;
    protected string|null $entity = null;
    protected string|null $entity_id = null;

    public function applyNewSourceCodeFileCreated(NewSourceCodeFileCreated $event)
    {
        $this->name = $event->source['file_nickname'];
        $this->path = $event->file['file_path'];
        $this->entity = $event->file['entity'];
        $this->entity_id = $event->file['entity_id'];
    }
    public function createNewSourceCodeFile(string $nickname, string $path, string $desc, string $source_id) : self
    {
        if(!is_null($this->path))
        {
            throw UploadedFileException::fileAlreadyExists();
        }

        $upload_payload = [
            'file_path' => $path,
            'entity_id' => $source_id,
            'entity'    => SourceCodeUpload::class,
            'active'    => 1
        ];

        $source_code_payload = [
            'id' => $source_id,
            'file_id' => $this->uuid(),
            'file_nickname' => $nickname,
            'description' => $desc,
            'active' => 1
        ];
        $this->recordThat(new NewSourceCodeFileCreated($this->uuid(), $upload_payload, $source_code_payload));
        return $this;
    }

    public function getFilePath() : string | null
    {
        return $this->path;
    }
}
