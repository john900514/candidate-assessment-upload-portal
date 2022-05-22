@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>Viewing Job Application for <span class="text-danger font-weight-bold">{!! $candidate_name !!}</span></h1>
            <h2>Job Position - <i>{!!  $job_title !!}</i></h2>
        </div>
    </section>
@endsection

@section('before_content_widgets')
    <div><h3 class="text-center"> Application Details </h3></div>
    @include(backpack_view('inc.widgets'), [ 'widgets' => $widgets['before_content'] ])
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="card text-white bg-info">
                    <div class="card-header">Candidate Info</div>
                    <div class="card-body">
                        <ul>
                            <li><span>Name:</span> <span class="font-bold">{!! $candidate_name !!}</span></li>
                            <li><span>Email:</span> <span class="font-bold">{!! $candidate_email !!}</span></li>
                            <li><span>Department:</span> <span class="font-bold">{!! $candidate_dept !!}</span></li>
                            <li><span>Register Date:</span> <span class="font-bold">{!! $register_date !!}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="card text-white bg-info">
                    <div class="card-header">{!! $candidate_name !!}'s Downloadables</div>
                    <div class="card-body">
                        <div class="text-center">
                            <a class="btn btn-success " href="{!! $resume_url !!}" target="_blank">Download Resume</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card text-black bg-white">
                    <div class="card-header">Submission Details</div>
                    <div class="card-body">
                        <application-submission :assessments="{{ json_encode($assessments) }}"></application-submission>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
