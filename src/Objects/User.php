<?php

namespace Oxemis\OxiSms\Objects;

use DateTime;
use stdClass;

/**
 * This class is used to help developpers.
 * It is mapped with the JSON returned by the API.
 */
class User extends ApiObject
{

    private string $companyName;
    private int $credits = 0;
    private ?DateTime $creditsValidBefore = null;
    private array $rates = [];

    /**
     * Name of the company or the organization.
     *
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    protected function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * Number of remaining on demand credits.
     *
     * @return int
     */
    public function getCredits(): int
    {
        return $this->credits;
    }

    protected function setCredits(int $credits): void
    {
        $this->credits = $credits;
    }

    /**
     * Expiration date of the remaining credit.
     *
     * @return DateTime|null
     */
    public function getCreditsValidBefore(): ?DateTime
    {
        return $this->creditsValidBefore;
    }

    protected function setCreditsValidBefore(string $creditsValidBefore): void
    {
        $this->creditsValidBefore = new DateTime($creditsValidBefore);
    }

    /**
     * List of rates. Each item as 3 properties :
     * - "Country" --> Name of the destination
     * - "CountryCode" --> Cuntry code
     * - "Cost" --> Cost (in credits) of each message sent to this destination.
     *
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    protected function setRates(array $rates): void
    {
        $this->rates = $rates;
    }

    /** Mapping can't be done automatically cause the JSON structure has a sub part "ResultDetails" */
    protected function myMapFromStdClass(stdClass $object)
    {

        $list = [];
        foreach ($object->Rates as $rate) {
            $list[] = ["Country" => $rate->Country, "CountryCode" => $rate->CountryCode, "Cost" => $rate->Cost];
        }
        $this->setRates($list);

    }

}
