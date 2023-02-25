<?php

use App\Cron\TestCronSendPubNub;

return [
    TestCronSendPubNub::class => [
        'enabled' => true,
        'interval' => 10,
    ],
];
