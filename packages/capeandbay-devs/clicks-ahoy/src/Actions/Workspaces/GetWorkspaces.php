<?php

namespace CapeAndBay\ClicksAhoy\Actions\Workspaces;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ixudra\Curl\Facades\Curl;
use Lorisleiva\Actions\Concerns\AsAction;

class GetWorkspaces
{
    use  AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {

    }

    public function asController(Request $request)
    {
        $token = env('CU_TOKEN');//$request->get('token');
        return $this->handle($token);
    }

    public function rules() : array
    {
        return [
            //'token' => 'required'
        ];
    }

    public function handle($token)
    {
        $results = false;

        $base_url = config('clicks-ahoy.api_url');
        $version  = config('clicks-ahoy.api_version');
        $url = "{$base_url}/{$version}/team";

        if(config('clicks-ahoy.caching', false))
        {
            $response = Cache::remember($token.'-GetWorkSpaces', (60 * 60) * 2, function() use($token, $url) {
                return Curl::to($url)
                    ->withHeaders([
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->asJson(true)
                    ->get();
            });
        }
        else
        {
            $response = Curl::to($url)
            ->withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->asJson(true)
            ->get();
        }


        if($response)
        {
            $results = $response;
        }

        return $results;
    }

    public function jsonResponse($result)
    {
        $code = 500;

        if ($result)
        {
            $code = 200;
        }

        return response($result, $code);
    }
}
