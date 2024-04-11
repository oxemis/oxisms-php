<?php

namespace Oxemis\OxiSMS\Objects;

use DateTime;

/**
 * This class is used to help developpers.
 * It is mapped with the JSON returned by the API.
 */
class ScheduledSending extends ApiObject
{

    private string $sendingId;
    private DateTime $scheduledDateTime;
    private int $nbSMS;
    private float $cost;
    private string $message;
    private string $campaign;

    /**
     * Get the internal ID of the sending.
     *
     * @return string
     */
    public function getSendingId(): string
    {
        return $this->sendingId;
    }

    protected function setSendingId(string $sendingId): void
    {
        $this->sendingId = $sendingId;
    }

    /**
     * Get the scheduled date and time.
     *
     * @return DateTime
     */
    public function getScheduledDateTime(): DateTime
    {
        return $this->scheduledDateTime;
    }

    protected function setScheduledDateTime(string $scheduledDateTime): void
    {
        $this->scheduledDateTime = new DateTime($scheduledDateTime);
    }

    /**
     * Get the number of recipients.
     *
     * @return int
     */
    public function getNbSMS(): int
    {
        return $this->nbSMS;
    }

    protected function setNbSMS(int $nbSMS): void
    {
        $this->nbSMS = $nbSMS;
    }

    /**
     * Get the total cost of the sending.
     *
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    protected function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * Get the message that will be sent.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    protected function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get the name of the campaign associated with the sending.
     *
     * @return string
     */
    public function getCampaign(): string
    {
        return $this->campaign;
    }

    protected function setCampaign(string $campaign): void
    {
        $this->campaign = $campaign;
    }

}
