<?php

declare(strict_types=1);

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

// Display Pretty Error Pages only in local environment
$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();
