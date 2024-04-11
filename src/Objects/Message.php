<?php

namespace Oxemis\OxiSMS\Objects;

use DateTime;
use DateTimeInterface;
use Exception;
use JsonSerializable;

class Message implements JsonSerializable
{

    public const ENCODING_GSM = "gsm";
    public const ENCODING_UNICODE = "unicode";
    public const ENCODING_AUTO = "auto";

    public const STRATEGY_COMMERCIAL = "commercial";
    public const STRATEGY_NOTIFICATION = "notification";

    /** @var array<Recipient> List of recipients */
    private array $recipients = array();

    /** @var bool Allows duplicates in recipients list */
    private bool $allowDuplicates = false;

    /** @var string Campaign name allows you to group sendings */
    private string $campaignName = "";

    /** @var DateTime|null The date and time you wish to send messages (if null: send immediately)
     * This date must be at least 5 minutes away and must not exceed 90 days.
     */
    private ?DateTime $scheduledDateTime = null;

    /** @var ?string (optional) The name of the sender of the SMS (2 to 11 characters, only ascii A-Z 0-9 and spaces).
     * If not provided, a short code (like 36111) is used.
     * Be careful ! Some networks don't accept a sender name and, if you use the "commercial" strategy,
     * an unsubscribe method will be added to your message ("STOP SMS 36111" for exemple).
     * This may increase the length of your message.
     */
    private ?string $sender = null;

    /** @var string Format of the message, can be "gsm" or "unicode".
     * Please read below important informations about the format and the impact on the sending cost :
     * https://faq.oxisms.com/?action=faq&cat=2&id=11
     */
    private string $encoding = self::ENCODING_AUTO;

    /** @var string The strategy is the type of the message you send.
     * Two values are accepted :
     * STRATEGY_COMMERCIAL (default): use this strategy for all commercial communications. A 'commercial' message is a message in which you promote your activities or events. It's not linked with the money. For example, when you promote a free event, you send a commercial message.
     * STRATEGY_NOTIFICATION : use this strategy for all other messages (for example a password reset SMS or a two factor authentication).
     * Please note : as required by the law, all sendings in 'commercial' strategy are not allowed in the evening after 9:00 p.m., in the morning before 8:00 a.m. as well as on Sundays and public holidays. They will not be rejected, they will be automatically postponed to the next available period. */
    private string $strategy = self::STRATEGY_COMMERCIAL;

    /** @var bool If the sms is received by a french landline, then it will be read to your recipient.
     * Only available to french recipients.
     */
    private bool $vocalization = false;

    /** @var int|null The maximum credit you want to spend to send.
     * If the credit that would be spent is greater than the value indicated, the sending is refused.
     */
    private ?int $maxCreditsPerSending = null;

    /** @var string The message you want to send */
    private string $message;

    /**
     * Return the message set to be send.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message content
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): Message
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the sender email address.
     *
     * @return null|string
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * Set the name of the sender of the SMS (2 to 11 characters, only ascii A-Z 0-9 and spaces).
     * If not provided, a short code (like 36111) is used.
     * Be careful ! Some networks don't accept a sender name and, if you use the "commercial" strategy,
     * an unsubscribe method will be added to your message ("STOP SMS 36111" for exemple).
     * This may increase the length of your message.
     *
     * @param null|string $sender
     * @return $this
     */
    public function setSender(?string $sender): Message
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get the list of recipients (as an array of "Recipient"s objects).
     *
     * @return Recipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Use this array of "Recipient"s objects as recipients.
     *
     * @param Recipient[] $recipients
     * @return $this
     */
    public function setRecipients(array $recipients): Message
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * Add this "Recipient" object as a recipent.
     *
     * @param Recipient $recipient
     * @return $this
     */
    public function addRecipient(Recipient $recipient): Message
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    /**
     * Add this phone number as a recipient of the message.
     *
     * @param string $phoneNumber
     * @return $this
     */
    public function addRecipientPhoneNumber(string $phoneNumber): Message
    {
        $r = new Recipient();
        $r->setPhoneNumber($phoneNumber);
        $this->recipients[] = $r;
        return $this;
    }

    /**
     * Are duplicates allowed in the message ?
     *
     * @return bool
     */
    public function isAllowDuplicates(): bool
    {
        return $this->allowDuplicates;
    }

    /**
     * Set it to true to allow duplicates.
     *
     * @param bool $allowDuplicates
     * @return $this
     */
    public function setAllowDuplicates(bool $allowDuplicates): Message
    {
        $this->allowDuplicates = $allowDuplicates;
        return $this;
    }

    /**
     * Get the campaign name.
     *  Campaign name allows you to group sendings.
     *
     * @return string
     */
    public function getCampaignName(): string
    {
        return $this->campaignName;
    }

    /**
     * Set the campaign name.
     * Campaign name allows you to group sendings.
     *
     * @param string $campaignName
     * @return $this
     */
    public function setCampaignName(string $campaignName): Message
    {
        $this->campaignName = $campaignName;
        return $this;
    }

    /**
     * Get the scheduled date and time.
     *
     * @return DateTime|null
     */
    public function getScheduledDateTime(): ?DateTime
    {
        return $this->scheduledDateTime;
    }

    /**
     * Set the date and time to send the message.
     *
     * @param DateTime|null $scheduledDateTime The date and time you wish to send messages (if null: send immediately)
     *  This date must be at least 5 minutes away and must not exceed 90 days.
     * @return $this
     */
    public function setScheduledDateTime(?DateTime $scheduledDateTime): Message
    {
        $this->scheduledDateTime = $scheduledDateTime;
        return $this;
    }

    /**
     * Get the message encoding.
     * Please read below important informations about the encoding and the impact on the sending cost :
     * https://faq.oxisms.com/?action=faq&cat=2&id=11
     *
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Set the format of the message.
     *
     * @param string $encoding Can be ENCODING_AUTO, ENCODING_GSM or ENCODING_UNICODE.
     *  Please read below important informations about the encoding and the impact on the sending cost :
     *  https://faq.oxisms.com/?action=faq&cat=2&id=11
     * @return $this
     * @throws Exception
     * @throws Exception
     */
    public function setEncoding(string $encoding): Message
    {
        $encoding = strtolower($encoding);
        if (($encoding != self::ENCODING_AUTO) && ($encoding != self::ENCODING_GSM) && ($encoding != self::ENCODING_UNICODE)) {
            throw new Exception("Invalid encoding (must be ENCODING_AUTO, ENCODING_GSM or ENCODING_UNICODE");
        }
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Get the strategy of the message. Can be STRATEGY_COMMERCIAL or STRATEGY_NOTIFICATION.
     *
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * Set the strategy of the message.
     *
     * @param string $strategy Two values are accepted :
     *  STRATEGY_COMMERCIAL (default): use this strategy for all commercial communications. A 'commercial' message is a message in which you promote your activities or events. It's not linked with the money. For example, when you promote a free event, you send a commercial message.
     *  STRATEGY_NOTIFICATION : use this strategy for all other messages (for example a password reset SMS or a two factor authentication).
     *  Please note : as required by the law, all sendings in 'commercial' strategy are not allowed in the evening after 9:00 p.m., in the morning before 8:00 a.m. as well as on Sundays and public holidays. They will not be rejected, they will be automatically postponed to the next available period.
     * @return void
     * @throws Exception
     * @throws Exception
     */
    public function setStrategy(string $strategy): Message
    {
        $strategy = strtolower($strategy);
        if (($strategy != self::STRATEGY_NOTIFICATION) && ($strategy != self::STRATEGY_COMMERCIAL)) {
            throw new Exception("Invalid strategy (must be STRATEGY_NOTIFICATION or STRATEGY_COMMERCIAL");
        }
        $this->strategy = $strategy;
        return $this;
    }

    /**
     * Get if the vocalization is enabled on the message.
     *
     * @return bool
     */
    public function isVocalization(): bool
    {
        return $this->vocalization;
    }

    /**
     * Set if the vocalization is enabled on the message.
     *
     * @param bool $vocalization If the sms is received by a french landline, then it will be read to your recipient.
     *  Only available to french recipients.
     * @return $this
     */
    public function setVocalization(bool $vocalization): Message
    {
        $this->vocalization = $vocalization;
        return $this;
    }

    /**
     * Return the max number of credits set for the message (null if no limit)
     *
     * @return int|null
     */
    public function getMaxCreditsPerSending(): ?int
    {
        return $this->maxCreditsPerSending;
    }

    /**
     * Set the max number of credits set for the message (null if no limit)
     *
     * @param int|null $maxCreditsPerSending The maximum credit you want to spend to send.
     *  If the credit that would be spent is greater than the value indicated, the sending is refused.
     * @return $this
     */
    public function setMaxCreditsPerSending(?int $maxCreditsPerSending): Message
    {
        $this->maxCreditsPerSending = $maxCreditsPerSending;
        return $this;
    }

    /**
     * Function used to serialize object
     *
     * @return array
     */
    public function jsonSerialize(): array
    {

        // Créer le tableau avec les données
        $json = array();

        // Ajouter les options
        if (!empty($this->campaignName)) {
            $json["Options"]["CampaignName"] = $this->campaignName;
        }

        // Scheduled Date
        if (!is_null($this->scheduledDateTime)) {
            $json["Options"]["ScheduledDateTime"] = $this->scheduledDateTime->format(DateTimeInterface::RFC3339);
        }

        // Max number of credits
        if (!is_null($this->maxCreditsPerSending)) {
            $json["Options"]["MaxCreditsPerSending"] = $this->maxCreditsPerSending;
        }

        $json["Options"]["Encoding"] = $this->encoding;
        $json["Options"]["Strategy"] = $this->strategy;
        $json["Options"]["Vocalization"] = $this->vocalization;
        $json["Options"]["AllowDuplicates"] = $this->allowDuplicates;

        // Ajouter le message
        $json["Message"] = [
            "Sender" => $this->getSender(),
            "Text" => $this->getMessage()
        ];

        // Recipients
        $json["Recipients"] = array();
        foreach ($this->recipients as $recipient) {

            $r["PhoneNumber"] = $recipient->getPhoneNumber();
            if (!empty($recipient->getMetaData())) {
                $r["MetaData "] = $recipient->getMetaData();
            }
            $json["Recipients"][] = $r;

        }

        return $json;

    }
}
