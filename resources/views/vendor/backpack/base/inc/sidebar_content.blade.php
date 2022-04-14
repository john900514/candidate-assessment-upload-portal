<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a>
</li>

@if(backpack_user()->can('view_employees') || backpack_user()->can('view_candidates'))
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('users') }}"><i class="las la-user-ninja nav-icon"></i> Users </a>
    </li>
@endif

@if(backpack_user()->can('create_job_positions'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('candidates/job-positions') }}'><i class='nav-icon las la-chalkboard-teacher'></i>Job Positions</a></li>
@endif

@if(backpack_user()->can('create_assessments'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('candidates/assessments') }}'><i class='nav-icon las la-chalkboard-teacher'></i>Assessments</a></li>
@endif

@if(backpack_user()->can('create_quiz_questions'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('assets/source-code') }}'><i class="las la-laptop-code"></i> Source Code</a></li>
@endif
