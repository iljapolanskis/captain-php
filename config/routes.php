<?php

use App\Controller\CurlController;
use App\Controller\HomeController;
use App\Controller\PubNubController;
use Slim\App;

return static function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
    $app->get('/curl', [CurlController::class, 'get']);
    // PubNub
    $app->post('/pubnub/publish', [PubNubController::class, 'publish']);
};
