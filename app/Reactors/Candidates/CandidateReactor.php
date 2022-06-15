<?php

namespace App\Reactors\Candidates;

use App\Actions\Assets\GenerateNdaPdf;
use App\Aggregates\Users\UserAggregate;
use App\Mail\Users\Candidates\Candidates\CandidateNDAMail;
use App\Mail\Users\Candidates\Employees\HR\CandidateNDAForHR;
use App\StorableEvents\Candidates\Registration\NDASubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class CandidateReactor extends Reactor implements ShouldQueue
{
    public function onNDASubmitted(NDASubmitted $event)
    {
        // Generate the PDF with the data from the event
        $pdf = GenerateNdaPdf::run($event->details);

        // Get the Data needed to send out the Mail Classes about the Candidate
        $aggy = UserAggregate::retrieve($event->user_id);

        $candidate_payload = [
            'candidate' => $event->details
        ];
        $hr_payload = [
            'first_name' => env('APP_ENV') == 'production' ? 'Amy' : 'Angel',
            'candidate' => $event->details
        ];

        $hr_payload['candidate']['email'] = $aggy->getEmail();

        // Trigger a mail to the Candidate
        Mail::to($aggy->getEmail())->send(new CandidateNDAMail($candidate_payload, $pdf));
        $hr_email = env('APP_ENV') == 'production' ? 'amy@capeandbay.com' : 'angel@capeandbay.com';

        // Trigger a mail to HR (Amy in Prod, Angel elsewhere)
        Mail::to($hr_email)->send(new CandidateNDAForHR($hr_payload, $pdf));
    }
}
