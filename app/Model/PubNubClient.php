<?php

namespace App\Model;

use PubNub\PubNub;

class PubNubClient
{
    public function __construct(
        private PubNub $pubNub,
        private PubNubSubscriber $pubNubSubscriber,
    ) {
        $this->pubNub->addListener($this->pubNubSubscriber);
    }

    public function publishToNewsChannel(string $message): void
    {
        $this->publish('ch-1', $message);
    }

    private function publish(string $channel, string $message): void
    {
        $this->pubNub->publish()
            ->channel($channel)
            ->message($message)
            ->sync();
    }

    public function subscribeToNewsChannel(): void
    {
        $this->subscribe('news');
    }

    private function subscribe(string $channel): void
    {
        $this->pubNub->subscribe()
            ->channels($channel)
            ->execute();
    }
}
