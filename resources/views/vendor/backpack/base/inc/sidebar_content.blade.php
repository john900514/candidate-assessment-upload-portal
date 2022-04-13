<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a>
</li>

@if(backpack_user()->can('view_employees') || backpack_user()->can('view_candidates'))
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('users') }}"><i class="las la-user-ninja nav-icon"></i> Users </a>
    </li>
@endif
