<?php

namespace App\Actions\Candidate\ToDos;

use App\Actions\InboundAPI\Assets\Files\SourceCode\GetUserScopedAvailableSourceCodes;
use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class GetToDoList
{
    use AsAction;

    public function handle(string $user_id)
    {
        $results = [];

        $aggy = UserAggregate::retrieve($user_id);

        if(!$aggy->getAccessToken())
        {
            $results[] = 'Generate your <a href="/portal/edit-account-info" class="link" target="_blank">access token</a>!';
        }

        if(!$aggy->hasDownloadedInstaller())
        {
            $results[] = 'Download the CLI Tool and login to get source code for your assessment(s)!';
        }
        else
        {
            $sources = GetUserScopedAvailableSourceCodes::run($user_id);
            if(count($sources) > 0)
            {
                foreach ($sources as $source)
                {
                    // @todo - check if the user downloaded this source code already
                    $results[] = 'Source Code Available from the CLI Tool - <b>'.$source['file_name'].'</b>';
                }
            }
        }

        return $results;
    }
}
