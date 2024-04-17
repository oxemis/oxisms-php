<?php

namespace Oxemis\OxiSms\Objects;

/**
 * This class is used to help developpers.
 * It is mapped with the JSON returned by the API.
 */
class Sending extends ApiObject
{

    private string $sendingId;
    private float $totalCost;
    private array $filtered;
    private array $invalid;
    private array $OK;

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
     * Get the total cost of the sending.
     *
     * @return float
     */
    public function getTotalCost(): float
    {
        return $this->totalCost;
    }

    protected function setTotalCost(float $totalCost): void
    {
        $this->totalCost = $totalCost;
    }

    /**
     * Get the filtered recipients.
     *
     * @return array
     */
    public function getFiltered(): array
    {
        return $this->filtered;
    }

    protected function setFiltered(array $filtered): void
    {
        $this->filtered = $filtered;
    }

    /**
     * Get the invalid recipients.
     *
     * @return array
     */
    public function getInvalid(): array
    {
        return $this->invalid;
    }

    protected function setInvalid(array $invalid): void
    {
        $this->invalid = $invalid;
    }

    /**
     * Get the valid recipients.
     *
     * @return array
     */
    public function getOK(): array
    {
        return $this->OK;
    }

    protected function setOK(array $ok): void
    {
        $this->OK = $ok;
    }

}
