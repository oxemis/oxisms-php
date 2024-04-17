<?php

namespace Oxemis\OxiSms\Components;

use Oxemis\OxiSms\OxiSmsClient;
use Oxemis\OxiSms\OxiSmsException;

/**
 * Class for https://api.oxisms.com/doc/#/blacklists
 */
class BlacklistsAPI extends Component
{

    public function __construct(OxiSmsClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * @param int $lastId           (Optional) Get the bounces added since this ID.
     * @return array<string>        List of blacklisted phone numbers (the items index in the array are the IDs of the blacklisted numbers).
     * @throws OxiSmsException
     */
    public function getBlacklistedPhoneNumbers(int $lastId = -1): ?array
    {
        $result = $this->request("GET", "/blacklist", ["lastid" => $lastId]);
        if (!is_null($result)) {
            $list = [];
            foreach ($result->data as $data) {
                $list[$data->Id] = $data->PhoneNumber;
            }
            return $list;
        } else {
            return null;
        }
    }

    /**
     * @param string $phoneNumber   Phone number to add (you can add multiple numbers separated by ';').
     * @return bool                 true means that the phone number has been added.
     * @throws OxiSmsException
     */
    public function addPhoneNumberToBlacklist(string $phoneNumber): bool
    {
        $result = ($this->request("POST", "/blacklist", ["phonenumbers " => $phoneNumber]));
        return ($result->Code == 200);
    }

    /**
     * @param string $phoneNumber   The phone number you want to remove.
     * @return bool                 true means that the phone number has been removed.
     * @throws OxiSmsException
     */
    public function deletePhoneNumberInBlacklist(string $phoneNumber): bool
    {
        $result = ($this->request("DELETE", "/blacklist", ["phonenumbers" => $phoneNumber]));
        return ($result->Code == 200);
    }

}
