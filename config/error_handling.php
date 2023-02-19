<?php

declare(strict_types=1);

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$whoops = new Run;
// Display Pretty Error Pages only in local environment
if ($_ENV['APP_ENV'] === 'local') {
    $whoops->pushHandler(new PrettyPageHandler);
} else {
    $whoops->pushHandler(function ($error) {
        // TODO: CREATE FRIENDLY ERROR PAGE
        echo 'Friendly error page and send an email to the developer';
    });
}
$whoops->register();
