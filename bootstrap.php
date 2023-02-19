<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/constants.php';

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

return require CONFIG_PATH . '/container/container.php';
