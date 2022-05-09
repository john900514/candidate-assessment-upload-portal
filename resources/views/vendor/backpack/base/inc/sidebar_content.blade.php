<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@php
    $user = backpack_user();
    $user_aggy = \App\Aggregates\Users\UserAggregate::retrieve($user->id)
@endphp
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a>
</li>

@if($user->can('view_employees') || backpack_user()->can('view_candidates'))
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('users') }}"><i class="las la-user-ninja nav-icon"></i> Users </a>
    </li>
@endif

@if($user->can('create_job_positions'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('candidates/job-positions') }}'><i class='nav-icon las la-chalkboard-teacher'></i>Job Positions</a></li>
@endif

@if($user->can('create_assessments'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('candidates/assessments') }}'><i class='nav-icon las la-chalkboard-teacher'></i>Assessments</a></li>
@endif

@if($user->can('create_quiz_questions'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('assets/source-code') }}'><i class="las la-laptop-code"></i> Source Code</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('assets/quizzes') }}'><i class='nav-icon las la-school'></i>Quizzes</a></li>
@endif

@if($user_aggy->isApplicant())
    <!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('assessments') }}'><i class="las la-pen-alt"></i> Assessments</a></li> -->
@endif
