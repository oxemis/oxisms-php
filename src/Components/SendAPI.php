<?php

namespace Oxemis\OxiSms\Components;

use Oxemis\OxiSms\OxiSmsClient;
use Oxemis\OxiSms\OxiSmsException;
use Oxemis\OxiSms\Objects\Message;
use Oxemis\OxiSms\Objects\ScheduledSending;
use Oxemis\OxiSms\Objects\Sending;

/**
 * Class for https://api.oxisms.com/doc/#/send
 */
class SendAPI extends Component
{

    /**
     * @param OxiSmsClient $apiClient
     */
    public function __construct(OxiSmsClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * @param Message $message  The Message you want to send.
     * @return Sending          Informations about the sending (see API doc for details).
     * @throws OxiSmsException
     */
    public function send(Message $message): Sending
    {
        return $this->sendJSON(json_encode($message));
    }

    /**
     * @param string $JSONMessage   The JSON representation of the message you want to send (see :https://api.oxisms.com/doc/#/send/post_send)
     * @return Sending              Informations about the sending (see API doc for details).
     * @throws OxiSmsException
     */
    public function sendJSON(string $JSONMessage): Sending
    {
        $result = $this->request("POST", "/send", null, $JSONMessage);
        return (Sending::mapFromStdClass($result));
    }

    /**
     * @param Message $message      The Message you want to send.
     * @return Sending              Information about the future cost of the sending (see API doc for details).
     * @throws OxiSmsException
     */
    public function getCostOfMessage(Message $message): Sending
    {
        // We don't care about this property when talking about cost
        $oldSetMaxCredits = $message->getMaxCreditsPerSending();
        $message->setMaxCreditsPerSending(null);

        // Get the JSON content
        $json = json_encode($message, JSON_PRETTY_PRINT);

        // Restore the property
        $message->setMaxCreditsPerSending($oldSetMaxCredits);

        // Run the query
        return $this->getCostOfMessageJSON($json);
    }

    /**
     * @param string $JSONMessage   The JSON representation of the message you want to send (see :https://api.oxisms.com/doc/#/send/post_send)
     * @return Sending              Information about the sending (see API doc for details).
     * @throws OxiSmsException
     */
    public function getCostOfMessageJSON(string $JSONMessage): Sending
    {
        $result = $this->request("POST", "/cost", null, $JSONMessage);
        return Sending::mapFromStdClass($result);
    }

    /**
     * @return array<ScheduledSending>|null           List of scheduled sendings.
     * @throws OxiSmsException
     */
    public function getScheduled(): ?array
    {
        $sendings = $this->request("GET", "/scheduled");
        if (!is_null($sendings)) {
            $list = [];
            foreach ($sendings as $sending) {
                $list[] = ScheduledSending::mapFromStdClass($sending);
            }
            return $list;
        } else {
            return null;
        }
    }

    /**
     * @param string $sendingID The ID of the sending you want to cancel.
     * @return bool
     * @throws OxiSmsException
     */
    public function deleteScheduled(string $sendingID): bool
    {
        $result = $this->request("DELETE", "/scheduled", ["sendingid" => $sendingID]);
        return ($result->Code == 200);
    }

}
