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
@endsection
