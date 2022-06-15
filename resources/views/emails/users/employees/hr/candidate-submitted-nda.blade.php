<div id="email" style="margin-top: 1em;">
    <p>Dear {!! $first_name !!},</p>

    <br />
    <p>Attached is an NDA from one {!! $candidate['first_name'] !!} {!! $candidate['last_name'] !!},</p>
    <p>a candidate invited to take an assessment for a position at Cape & Bay.</p>

    <br />

    <p>Enclosed you will find the signed NDA to download.</p>

    <br />
    <br />

    <p> Please reach out to the applicant if there are any issues <a href="mailto:{!! $candidate['email'] !!}">here</a>.</p>
    <p> Please reach out to Angel if the NDA need to be re-signed <a href="mailto:angel@capeandbay.com">here</a>.</p>

    <br />
    <br />

    <p style="margin-left: 2em;">Sincerely,</p>

    <br />
    <br />

    <p style="margin-left: 2em;"><b>The Cape & Bay Developer Team</b></p>

    <br />
    <br />
    @if(env('APP_ENV') != 'production')
        <p><b>NOTICE: This is a test email using test data. Do not follow up.</b></p>
    @endif
</div>
