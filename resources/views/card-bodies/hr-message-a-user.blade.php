<table class="table table-responsive-sm table-striped mb-0">
    <thead class="thead-light">
    <tr>
        <th class="text-center">Candidate</th>
        <th>Job Position</th>
        <th class="text-center">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(count($candidates) > 0)
    @foreach($candidates as $candidate)
    <tr>
        <td class="text-center">
            <div class="avatar"><img class="img-avatar" src="img/avatars/1.jpg" alt="admin@bootstrapmaster.com"><span class="avatar-status badge-success"></span></div>
        </td>
        <td>
            <div>Yiorgos Avraamu</div>
            <div class="small text-muted"><span>New</span> | Registered: Jan 1, 2015</div>
        </td>
        <td class="text-center"><i class="flag-icon flag-icon-us h4 mb-0" id="us" title="us"></i></td>
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
