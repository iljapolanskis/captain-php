<?php

use App\Controller\AuthController;
use App\Controller\CurlController;
use App\Controller\HomeController;
use App\Controller\PubNubController;
use Slim\App;

return static function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
    $app->get('/curl', [CurlController::class, 'get']);
    // PubNub
    $app->post('/pubnub/publish', [PubNubController::class, 'publish']);

    // Auth
    $app->get('/auth/login', [AuthController::class, 'loginView']);
    $app->get('/auth/register', [AuthController::class, 'registerView']);
    $app->post('/auth/login', [AuthController::class, 'login']);
    $app->post('/auth/register', [AuthController::class, 'register']);
};
