<?php

namespace App\Aggregates\Users\Partials;

use App\Exceptions\Users\UserAuthException;
use App\Models\User;
use App\StorableEvents\Users\Activity\API\AccessTokenGranted;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class AccessTokenPartial extends AggregatePartial
{
    protected bool $token_acquired = false;

    public function applyAccessTokenGranted(AccessTokenGranted $event)
    {
        $this->token_acquired = true;
    }

    public function getAccessToken() : string | false
    {
        $results = false;

        if($this->token_acquired)
        {
            $token_detail = backpack_user()->api_token()->first();

            if(!is_null($token_detail))
            {
                $results = base64_decode($token_detail->value);
            }
        }

        return $results;
    }

    public function grantAccessToken() : self
    {
        // @todo - do some timing validation here
        if(false == true)
        {
            throw UserAuthException::accessTokenPermissionDenied();

        }

        $this->recordThat(new AccessTokenGranted($this->aggregateRootUuid()));

        return $this;
    }
}
