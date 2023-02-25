<?php

declare(strict_types=1);

use App\Cron\TestCronSendPubNub;

$container = require __DIR__ . '/../bootstrap.php';

$container->get(TestCronSendPubNub::class)->run();
