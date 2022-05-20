<table class="table table-responsive-sm table-striped mb-0">
    <thead class="thead-light">
    <tr>
        <th class="text-center">Candidate</th>
        <th>Job Position</th>
        <th>Apply Date</th>
        <th class="text-center">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(count($candidates) > 0)
        @foreach($candidates as $candidate)
            <tr>
                <td class="text-center">
                    <div class="small text-muted"><span>{!! $candidate['user']['first_name'] !!} {!! $candidate['user']['last_name'] !!}</span></div>
                </td>
                <td>
                    <div></div>
                    <div class="small text-muted"><span>{!! $candidate['position']['title'] !!}</span></div>
                </td>
                <td>
                    <div></div>
                    <div class="small text-muted">{!! $candidate['position']['date'] !!}</div>
                </td>
                <td class="text-center"><button type="button" class="btn btn-sm bg-info" onclick="window.location.href = '/portal/users/{!! $candidate['user']['id'] !!}/application?job={!! $candidate['position']['id'] !!}'">View</button></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td class="text-right">
                No Candidates
            </td>
            <td class="text-center">
                to
            </td>
            <td class="text-left">Review</td>
        </tr>
    @endif
    </tbody>
</table>
