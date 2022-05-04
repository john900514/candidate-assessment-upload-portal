<?php

namespace CapeAndBay\ClicksAhoy\Services;

use CapeAndBay\ClicksAhoy\Actions\Spaces\GetSpace;
use CapeAndBay\ClicksAhoy\Actions\Spaces\GetSpaces;
use CapeAndBay\ClicksAhoy\Actions\Workspaces\GetWorkspaces;

class ClickUpAPIService
{
    protected $access_token;

    public function __construct()
    {

    }

    public function setAccessToken(string $token)
    {
        $this->access_token = $token;
    }

    public function Workspaces()
    {
        $results = false;

        if (!is_null($this->access_token))
        {
            $results = GetWorkspaces::run($this->access_token);
        }

        return $results;
    }

    public function Spaces(int $team_id)
    {
        $results = false;

        if (!is_null($this->access_token))
        {
            $results = GetSpaces::run($this->access_token, $team_id);
        }

        return $results;
    }

    public function Space(int $space_id)
    {
        $results = false;

        if (!is_null($this->access_token))
        {
            $results = GetSpace::run($this->access_token, $space_id);
        }

        return $results;
    }

    public function CreateSpace() {}
    public function UpdateSpace() {}
    public function DeleteSpace() {}
}
