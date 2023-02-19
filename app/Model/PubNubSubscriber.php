<?php

namespace App\Model;

use PubNub\Callbacks\SubscribeCallback;
use PubNub\Enums\PNStatusCategory;

class PubNubSubscriber extends SubscribeCallback
{
    public function status($pubnub, $status)
    {
        if ($status->getCategory() === PNStatusCategory::PNUnexpectedDisconnectCategory) {
            // This event happens when radio / connectivity is lost
        } else if ($status->getCategory() === PNStatusCategory::PNConnectedCategory) {
            // Connect event. You can do stuff like publish, and know you'll get it
            // Or just use the connected event to confirm you are subscribed for
            // UI / internal notifications, etc
        } else if ($status->getCategory() === PNStatusCategory::PNDecryptionErrorCategory) {
            // Handle message decryption error. Probably client configured to
            // encrypt messages and on live data feed it received plain text.
        }
    }

    public function message($pubnub, $message)
    {
        // Handle new message stored in message.message
        print_r($message->getMessage() . PHP_EOL);
        print_r($message->getPublisher() . PHP_EOL);
    }

    public function presence($pubnub, $presence)
    {
        // Handle incoming presence data. No-op in this case.
    }
}