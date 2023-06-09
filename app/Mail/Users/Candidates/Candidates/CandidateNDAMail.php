<?php

namespace App\Mail\Users\Candidates\Candidates;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateNDAMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public array $payload, public $pdf)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $first = $this->payload['candidate']['first_name'];
        $last = $this->payload['candidate']['last_name'];
        return $this->from(
            env('MAIL_FROM_ADDRESS','developers@capeandbay.com'),
            env('MAIL_FROM_NAME', 'Cape & Bay Dev Team')
        )->subject('Here is a copy of the non-disclosure agreement you signed!')
            ->attachData($this->pdf->output(), "CNB-{$first}{$last}NDA.pdf")
            ->view('emails.users.candidates.thank-you-for-submitting-the-nda');
    }
}
