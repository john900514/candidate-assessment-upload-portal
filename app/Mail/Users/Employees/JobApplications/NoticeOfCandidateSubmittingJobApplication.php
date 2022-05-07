<?php

namespace App\Mail\Users\Employees\JobApplications;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeOfCandidateSubmittingJobApplication extends Mailable
{
    use Queueable, SerializesModels;

    protected string $job_title;
    protected UserAggregate $candidate_aggy;
    protected UserAggregate $user_aggy;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected string $user_id, protected array $details, protected string $date)
    {
        $job_aggy = JobPositionAggregate::retrieve($details['job']);
        $this->job_title = $job_aggy->getJobTitle();
        $this->candidate_aggy = UserAggregate::retrieve($this->details['candidate']);
        $this->user_aggy = UserAggregate::retrieve($this->details['candidate']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'job_id' => $this->details['job'],
            'job_title' => $this->job_title,
            'date' => $this->date,
            'user' => [
                'first_name' => $this->user_aggy->getFirstName(),
                'last_name' => $this->user_aggy->getLastName(),
            ],
            'candidate' => [
                'id' => $this->details['candidate'],
                'first_name' => $this->candidate_aggy->getFirstName(),
                'last_name' => $this->candidate_aggy->getLastName(),
                'email' => $this->candidate_aggy->getEmail()
            ]
        ];

        return $this->from(
            env('MAIL_FROM_ADDRESS','developers@capeandbay.com'),
            env('MAIL_FROM_NAME', 'Cape & Bay Dev Team')
        )->subject("A new candidate has applied for {$this->job_title}!")
            ->view('emails.users.employees.job-applications.notice-of-application-submission', $data);
    }
}
