<?php

declare(strict_types=1);

// Display Pretty Error Pages only in local environment
if ($_ENV['APP_ENV'] === 'local') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}
