<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Valitron\Validator;

class AuthController
{
    public function __construct(
        private Twig $twig,
        private EntityManager $entityManager,
    ) {}

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/register.twig');
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
//        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $validator = new Validator($data);
        $validator->rule('required', ['name', 'email', 'password', 'passwordConfirm']);
        $validator->rule('email', 'email');
        $validator->rule('equals', 'password', 'passwordConfirm')->message('Passwords do not match.');
        $validator->rule(function ($field, $value, $params, $fields) {
            return $this->entityManager->getRepository(User::class)->count(['email' => $value]) === 0;
        }, 'email')->message('Email is already taken.');
        $validator->rule(function ($field, $value, $params, $fields) {
            return $this->entityManager->getRepository(User::class)->count(['name' => $value]) === 0;
        }, 'name')->message('Username is already taken.');


        if (! $validator->validate()) {
            throw new ValidationException($validator->errors());
        }

        $user = new User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
//        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
