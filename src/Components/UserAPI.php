<?php

namespace Oxemis\OxiSMS\Components;

use Oxemis\OxiSMS\ApiClient;
use Oxemis\OxiSMS\ApiException;
use Oxemis\OxiSMS\Objects\User;

/**
 * Class for https://api.oxisms.com/doc/#/user
 */
class UserAPI extends Component
{

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * Get informations about your account.
     *
     * @return User             Current user information (see https://api.oxisms.com/doc/#/user).
     * @throws ApiException
     */
    public function getUser(): object
    {
        $o = $this->request("GET", "/user");
        return User::mapFromStdClass($o);
    }

}
