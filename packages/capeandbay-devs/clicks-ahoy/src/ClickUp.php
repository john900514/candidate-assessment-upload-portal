<?php

namespace CapeAndBay\ClicksAhoy;

use CapeAndBay\ClicksAhoy\Services\ClickUpAPIService;

class ClickUp
{
    protected $api_service;

    public function __construct(ClickUpAPIService $service)
    {
        $this->api_service = $service;
    }

    public function get(string $call, string $token, ...$arguments)
    {
        $this->api_service->setAccessToken($token);
        return $this->api_service->$call(...$arguments);
    }
}
