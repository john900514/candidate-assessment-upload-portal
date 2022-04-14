<div class="col-12">
    <p class="text-center"> Install the CLI Tool to download source code for assessments part of your open position(s).</p>
    <div>
        <form method="POST" action="{{ backpack_url('assets/download-installer') }}">
            @csrf
            <p class="text-center">
                <button type="submit" class="btn btn-success"><i class="las la-download"></i> Download</button>
            </p>
        </form>
    </div>
</div>
