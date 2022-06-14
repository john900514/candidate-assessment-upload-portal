@if($entry->employee_status->value != 'employee')
<form method="POST" action="{{ backpack_url('/users/'.$entry->getKey().'/impersonate') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-link"><i class="la la-eye"></i> {{ $button->name }}</button>
</form>
@endif

