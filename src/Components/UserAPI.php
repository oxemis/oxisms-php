<?php

namespace Oxemis\OxiSms\Components;

use Oxemis\OxiSms\OxiSmsClient;
use Oxemis\OxiSms\OxiSmsException;
use Oxemis\OxiSms\Objects\User;

/**
 * Class for https://api.oxisms.com/doc/#/user
 */
class UserAPI extends Component
{

    public function __construct(OxiSmsClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * Get informations about your account.
     *
     * @return User             Current user information (see https://api.oxisms.com/doc/#/user).
     * @throws OxiSmsException
     */
    public function getUser(): object
    {
        $o = $this->request("GET", "/user");
        return User::mapFromStdClass($o);
    }

}
