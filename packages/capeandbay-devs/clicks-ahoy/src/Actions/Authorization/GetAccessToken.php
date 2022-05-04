<?php

namespace CapeAndBay\ClicksAhoy\Actions\Authorization;

use Illuminate\Support\Facades\Cache;
use Ixudra\Curl\Facades\Curl;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAccessToken
{
    use  AsAction;

    public function rules() : array
    {
        return [
            'client_id' => ['bail', 'required', 'string'],
            'client_secret' => ['bail', 'required', 'string']
        ];
    }

    public function asController() : string|false
    {
        $results = false;

        if(request()->has('client_id') && request()->has('client_secret'))
        {
            $results =  $this->handle(request()->get('client_id'),request()->get('client_secret'));
        }

        return $results;
    }

    public function handle(string $client_id, string $client_secret) : string|false
    {
        $results = false;

        $base_url = config('clicks-ahoy.api_url');
        $version  = config('clicks-ahoy.api_version');
        $code = env('APP_URL');
        $url = "{$base_url}/{$version}/oauth/token?client_id={$client_id}&client_secret={$client_secret}&code={$code}";

        if(config('clicks-ahoy.caching', false))
        {
            /*
            $response = Cache::remember($token.'-GetSpace-'.$space_id, (60 * 60) * 2, function() use($token, $url) {
                return Curl::to($url)
                    ->withHeaders([
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->asJson(true)
                    ->get();
            });
            */
        }
        else
        {
            $response = Curl::to($url)
                ->withHeaders([

                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->asJson(true)
                ->get();

            dd($response);
        }


        if($response)
        {
            $results = $response;
        }

        return $results;
    }

    public function jsonResponse(string|false $result)
    {
        $results = false;
        $code = 500;

        if($result)
        {
            $results = $result;
            $code = 200;
        }

        return response($results, $code);
    }
}
