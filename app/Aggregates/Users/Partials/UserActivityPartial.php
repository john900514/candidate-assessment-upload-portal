<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Users\Activity\Email\UserSentWelcomeEmail;
use App\StorableEvents\Users\Activity\Files\UserDownloadedSourceCodeInstaller;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class UserActivityPartial extends AggregatePartial
{
    protected array $files_downloaded = [];
    protected array $activity = [];

    public function applyUserDownloadedSourceCodeInstaller(UserDownloadedSourceCodeInstaller $event)
    {
        if(!array_key_exists('Source Code Installer', $this->files_downloaded))
        {
            $this->files_downloaded['Source Code Installer'] = [];
        }

        $this->files_downloaded['Source Code Installer'][] = $event->date;
    }

    public function applyUserSentWelcomeEmail(UserSentWelcomeEmail $event)
    {
        $this->activity[] = 'Welcome email sent on '.$event->createdAt();
    }

    public function downloadSourceCodeInstaller() : self
    {
        $date = date('Y-m-d H:i:s');
        $this->recordThat(new UserDownloadedSourceCodeInstaller($this->aggregateRootUuid(), $date));
        return $this;
    }

    public function sendWelcomeEmail(string $status) : self
    {
        $this->recordThat(new UserSentWelcomeEmail($this->aggregateRootUuid(), $status));
        return $this;
    }
}
