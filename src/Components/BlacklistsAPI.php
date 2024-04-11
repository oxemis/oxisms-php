<?php

namespace Oxemis\OxiSMS\Components;

use Oxemis\OxiSMS\ApiClient;
use Oxemis\OxiSMS\ApiException;

/**
 * Class for https://api.oxisms.com/doc/#/blacklists
 */
class BlacklistsAPI extends Component
{

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * @param int $lastId           (Optional) Get the bounces added since this ID.
     * @return array<string>        List of blacklisted phone numbers (the items index in the array are the IDs of the blacklisted numbers).
     * @throws ApiException
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
     * @throws ApiException
     */
    public function addPhoneNumberToBlacklist(string $phoneNumber): bool
    {
        $result = ($this->request("POST", "/blacklist", ["phonenumbers " => $phoneNumber]));
        return ($result->Code == 200);
    }

    /**
     * @param string $phoneNumber   The phone number you want to remove.
     * @return bool                 true means that the phone number has been removed.
     * @throws ApiException
     */
    public function deletePhoneNumberInBlacklist(string $phoneNumber): bool
    {
        $result = ($this->request("DELETE", "/blacklist", ["phonenumbers" => $phoneNumber]));
        return ($result->Code == 200);
    }

}
