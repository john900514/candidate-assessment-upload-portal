<?php

namespace App\Actions\Candidate\Users;

use App\Aggregates\Users\UserAggregate;
use Lorisleiva\Actions\Concerns\AsAction;

class SubmitNDA
{
    use AsAction;

    public function rules() : array
    {
        return [
            'address'   => 'bail|required|string',
            'city'      => 'bail|required|string',
            'state'     => 'bail|required|string',
            'zip'       => 'bail|required|string',
            'signature' => 'bail|required|string',
        ];
    }

    public function handle(string $address, string $city, string $state, string $zip, string $signature) : bool
    {
        $results = false;

        // generate the date
        $date = date('Y-m-d H:i:s');

        // Get Aggregate
        $aggy = UserAggregate::retrieve(backpack_user()->id);

        // Check is NDA is not signed or return true
        if($aggy->isApplicant())
        {
            if(!$aggy->hasSignedNDA())
            {
                $payload = [
                    'first_name' => $aggy->getFirstName(),
                    'last_name' => $aggy->getLastName(),
                    'address'   => $address,
                    'city'      => $city,
                    'state'     => $state,
                    'zip'       => $zip,
                    'signature' => $signature,
                    'date' => [
                        'date' => $date,
                        'time' => date('d F', strtotime($date)),
                        'year' => date('Y', strtotime($date))
                    ],
                ];

                // Submit the signature and details to ES
                $aggy->submitNDA($payload)->persist();

                // Return true
                $results = true;
            }
            else
            {
                $results = true;
            }
        }

        return $results;
    }

    public function asController() : bool
    {
        $data = request()->all();
        return $this->handle($data['address'], $data['city'],$data['state'],$data['zip'],$data['signature']);
    }

    public function jsonResponse(bool $result)
    {
        $results = $result;
        $code = $result ? 200 : 500;

        return response($results, $code);
    }
}
