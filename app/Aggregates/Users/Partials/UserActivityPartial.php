<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Candidates\Assessments\SourceCodeSubmittedForAssessment;
use App\StorableEvents\Users\Activity\Email\EmailNeedsToBeFired;
use App\StorableEvents\Users\Activity\Email\UserSentWelcomeEmail;
use App\StorableEvents\Users\Activity\Files\UserDownloadedSourceCodeInstaller;
use App\StorableEvents\Users\Applicants\ApplicantUploadedResume;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class UserActivityPartial extends AggregatePartial
{
    protected array $files_downloaded = [];
    protected array $files_uploaded = [];
    protected array $activity = [];
    protected bool $installer_downloaded_before = false;

    public function applyUserDownloadedSourceCodeInstaller(UserDownloadedSourceCodeInstaller $event)
    {
        if(!array_key_exists('Source Code Installer', $this->files_downloaded))
        {
            $this->files_downloaded['Source Code Installer'] = [];
            $this->installer_downloaded_before = true;
        }

        $this->files_downloaded['Source Code Installer'][] = $event->date;
    }

    public function applyUserSentWelcomeEmail(UserSentWelcomeEmail $event)
    {
        $this->activity[] = 'Welcome email sent on '.$event->createdAt();
    }

    public function applyApplicantUploadedResume(ApplicantUploadedResume $event)
    {
        $this->files_uploaded['resume'] = $event->path;
    }

    public function applySourceCodeSubmittedForAssessment(SourceCodeSubmittedForAssessment $event)
    {
        $this->files_uploaded[$event->file_id] = $event->path;
    }

    public function applyEmailNeedsToBeFired(EmailNeedsToBeFired $event)
    {
        $this->activity[] = $event->mail_class.' fired out to user on '.$event->date;
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

    public function fireEmailToThisUser(string $mail_class, array $payload) : self
    {
        $date = date('Y-m-d H:i:s');
        $this->recordThat(new EmailNeedsToBeFired($this->aggregateRootUuid(), $mail_class, $payload, $date));
        return $this;
    }

    public function hasDownloadedInstaller() : bool
    {
        return $this->installer_downloaded_before;
    }
}
