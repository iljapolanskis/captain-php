<?php

namespace App\Cron;

use App\Api\CronJobInterface;
use App\Model\PubNubClient;

class TestCronSendPubNub implements CronJobInterface
{
    public function __construct(
        private PubNubClient $pubNubClient
    ) {}

    public function run(): void
    {
        $this->pubNubClient->publishToNewsChannel('Hello from cron On Local');
    }
}
