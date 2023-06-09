<?php

namespace App\Actions\Assets;

use App\Aggregates\Users\UserAggregate;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class DownloadInstallerAction
{
    use AsAction;

    public function handle()
    {
        UserAggregate::retrieve(backpack_user()->id)
            ->downloadSourceCodeInstaller()
            ->persist();

        switch(env('APP_ENV'))
        {
            case 'production':
                $object_path = 'candidate_assessment/installer/installer.zip';
                break;

            case 'develop':
                $object_path = 'candidate_assessment/installer/installer_alpha.zip';
                break;

            default:
                $object_path = 'candidate_assessment/installer/installer_dev.zip';
        }

        $url = Storage::disk('s3')->temporaryUrl($object_path, now()->addMinutes(10));
        return redirect($url);
    }
}
