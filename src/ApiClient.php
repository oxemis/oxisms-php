<?php

namespace Oxemis\OxiSMS;

use Oxemis\OxiSMS\Components\SendAPI;
use Oxemis\OxiSMS\Components\UserAPI;

/**
 * API Client for OxiSMS
 */
class ApiClient
{

    private string $auth;
    private string $userAgent;
    private string $baseURL;

    public function __construct(string $apiLogin, string $apiPassword)
    {

        $this->auth = base64_encode($apiLogin . ":" . $apiPassword);
        $this->userAgent = Configuration::USER_AGENT . PHP_VERSION . '/' . Configuration::WRAPPER_VERSION;
        $this->baseURL = "https://" . Configuration::MAIN_URL;
        $this->userAPI = new UserAPI($this);
        $this->sendAPI = new SendAPI($this);

    }

    public function getAuth(): string
    {
        return $this->auth;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getBaseURL(): string
    {
        return $this->baseURL;
    }

}
