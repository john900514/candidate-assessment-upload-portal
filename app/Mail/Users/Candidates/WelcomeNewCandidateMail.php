<?php

namespace App\Mail\Users\Candidates;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewCandidateMail extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected string $user_id)
    {
        $this->user = User::find($this->user_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'new_user' => $this->user
        ];
        return $this->from(
            env('MAIL_FROM_ADDRESS','developers@capeandbay.com'),
            env('MAIL_FROM_NAME', 'Cape & Bay Dev Team')
        )->subject('You have been invited to take a Cape & Bay hiring assessment!')
            ->view('emails.users.candidates.new-candidate-finish-registration', $data);
    }
}
