<div id="email" style="margin-top: 1em;">
    <p>Dear, Candidate!</p>

    <br />
    <p>You have been invited to complete a hiring assessment for an open position</p>
    <p>at Cape & Bay.</p>

    <br />

    <p>In order to take the assessment, an account was created for you. You will need to complete the registration in order to access your account.</p>
    <p> Click <a href="{!! env('APP_URL') !!}/portal/registration?session={!! $new_user->id !!}">here</a> or paste the URL below into your browser to begin!</p>
    <p><a href="{!! env('APP_URL') !!}/portal/registration?session={!! $new_user->id !!}">{!! env('APP_URL') !!}/portal/registration?session={!! $new_user->id !!}</a></p>

    <br />
    <br />

    <p> Wishing you the best of luck!</p>

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
