<?php

use App\Controller\AuthController;
use App\Controller\CurlController;
use App\Controller\HomeController;
use App\Middleware\Routes\AuthorizeMiddleware;
use App\Middleware\Routes\GuestMiddleware;
use Slim\App;

return static function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthorizeMiddleware::class);
    $app->get('/curl', [CurlController::class, 'get'])->add(AuthorizeMiddleware::class);

    // Auth
    $app->get('/auth/login', [AuthController::class, 'loginView'])->add(GuestMiddleware::class);
    $app->post('/auth/login', [AuthController::class, 'login'])->add(GuestMiddleware::class);

    $app->get('/auth/register', [AuthController::class, 'registerView'])->add(GuestMiddleware::class);
    $app->post('/auth/register', [AuthController::class, 'register'])->add(GuestMiddleware::class);

    $app->get('/auth/logout', [AuthController::class, 'logout'])->add(AuthorizeMiddleware::class);
};
