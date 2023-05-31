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
    $app->get('/dashboard', [UserController::class, 'dashboard'])
        ->setName('dashboard')
        ->add(AuthorizeMiddleware::class);

    // Posts
    $app->post('post', [PostController::class, 'create'])
        ->setName('post.save')
        ->add(AuthorizeMiddleware::class);
    $app->get('/post/view[/{slug}]', [PostController::class, 'view'])
        ->setName('post.view');
    $app->get('/post/list[/{category}]', [PostController::class, 'list'])
        ->setName('post.list');
    $app->get('/post/edit[/{id}]', [PostController::class, 'edit'])
        ->setName('post.edit')
        ->add(AuthorizeMiddleware::class);
    $app->post('/post/edit[/{id}]', [PostController::class, 'save'])
        ->setName('post.save')
        ->add(AuthorizeMiddleware::class);
    $app->post('/post/delete', [PostController::class, 'delete'])
        ->setName('post.delete')
        ->add(AuthorizeMiddleware::class);
};
