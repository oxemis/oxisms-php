<?php

namespace Oxemis\OxiSms\Objects;

class Recipient
{

    /** @var string Recipient phone number. */
    private string $phoneNumber;

    /** @var null|array (Optional) Meta Data of the recipient (used for mapping). */
    private ?array $metaData = null;

    /**
     * Get the phone number of the recipient.
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Set the phone number of the recipient.
     * You should use the international MSISDN format (33601020304) but you can also use
     * the national number if this number is a French Number (0601020304).
     * The number is "cleaned" from special chars (like "." ou "+").
     *
     * @param string $phoneNumber
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get the meta data associated with the recipient.
     *
     * @return array|null
     */
    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    /**
     * Set the meta data :  Optional and additional information about the recipient.
     * These information can be used in the message.
     * For example, in your message you can use {{CustomerName}} if you have a meta data 'CustomerName'.
     *
     * @param array|null $metaData
     * @return void
     */
    public function setMetaData(?array $metaData): void
    {
        $this->metaData = $metaData;
    }

}
