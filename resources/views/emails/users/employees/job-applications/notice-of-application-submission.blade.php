<div id="email" style="margin-top: 1em;">
    <p>Dear, {!! $user['first_name'] !!} {!! $user['last_name'] !!}!</p>

    <br />
    <p>A candidate has submitted an application for this {!! $job_title !!} position at {!! $date !!}.</p>

    <br />

    <p>Name {!! $candidate['first_name'] !!} {!! $candidate['last_name'] !!}</p>
    <p>Email {!! $candidate['email'] !!}</p>

    <p> Click <a href="{!! env('APP_URL') !!}/portal/users/{!! $candidate['id'] !!}/application?job={!! $job_id !!}">here</a> to review the application!</p>
    <p><a href="{!! env('APP_URL') !!}/portal/users/{!! $candidate['id'] !!}/application?job={!! $job_id !!}">{!! env('APP_URL') !!}/portal/users/{!! $candidate['id'] !!}/application?job={!! $job_id !!}</a></p>

    <br />
    <br />

    <br />
    <br />

    <p style="margin-left: 2em;">Sincerely,</p>

    <br />
    <br />

    <p style="margin-left: 2em;"><b>AngelBot from Cape & Bay's Portal Dev</b></p>

    <br />
    <br />
    @if(env('APP_ENV') != 'production')
        <p><b>NOTICE: This is a test email using test data. Do not follow up.</b></p>
    @endif
</div>
