<?php

use App\Controller\AuthController;
use App\Controller\CurlController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\UserController;
use App\Middleware\Routes\AuthorizeMiddleware;
use App\Middleware\Routes\GuestMiddleware;
use Slim\App;

return static function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
    $app->get('/curl', [CurlController::class, 'get'])->add(AuthorizeMiddleware::class);

    // Auth
    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestMiddleware::class);
    $app->post('/login', [AuthController::class, 'login'])->add(GuestMiddleware::class);

    $app->get('/register', [AuthController::class, 'registerView'])->add(GuestMiddleware::class);
    $app->post('/register', [AuthController::class, 'register'])->add(GuestMiddleware::class);

    $app->post('/logout', [AuthController::class, 'logout'])->add(AuthorizeMiddleware::class);

    // Profile
    $app->get('/profile', [UserController::class, 'index'])->add(AuthorizeMiddleware::class);

    // Posts
    $app->post('post', [PostController::class, 'create'])->add(AuthorizeMiddleware::class);
    $app->get('/post/edit[/{id}]', [PostController::class, 'edit'])->add(AuthorizeMiddleware::class);
    $app->post('/post/edit[/{id}]', [PostController::class, 'edit'])->add(AuthorizeMiddleware::class);
    $app->post('/post/delete', [PostController::class, 'delete']);
    $app->get('/post/view[/{slug}]', [PostController::class, 'view']);
    $app->get('/post[/{category}]', [PostController::class, 'list']);
};
