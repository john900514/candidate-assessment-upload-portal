@if(is_null($entry->email_verified_at))
<form method="POST" action="{{ backpack_url('/users/'.$entry->getKey().'/resend-email') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-link"><i class="la la-eye"></i> {{ $button->name }}</button>
</form>
@endif

