<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Users\Activity\Files\UserDownloadedSourceCodeInstaller;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class UserActivityPartial extends AggregatePartial
{
    protected array $files_downloaded = [];

    public function applyUserDownloadedSourceCodeInstaller(UserDownloadedSourceCodeInstaller $event)
    {
        if(!array_key_exists('Source Code Installer', $this->files_downloaded))
        {
            $this->files_downloaded['Source Code Installer'] = [];
        }

        $this->files_downloaded['Source Code Installer'][] = $event->date;
    }
    public function downloadSourceCodeInstaller() : self
    {
        $date = date('Y-m-d H:i:s');
        $this->recordThat(new UserDownloadedSourceCodeInstaller($this->aggregateRootUuid(), $date));
        return $this;
    }
}
