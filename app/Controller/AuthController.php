<?php

namespace App\Controller;

use App\Api\AuthInterface;
use App\Api\RequestValidatorFactoryInterface;
use App\DTO\RegisterUserData;
use App\DTO\Validator\LoginUserDataValidator;
use App\DTO\Validator\RegisterUserDataRequestValidator;
use App\Exception\ValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AuthController
{

    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly AuthInterface $auth,
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
        $data = $this->requestValidatorFactory
            ->make(LoginUserDataValidator::class)
            ->validate($request->getParsedBody());

        if (! $this->auth->attemptLogin($data)) {
            throw new ValidationException(['password' => ['Invalid credentials.']]);
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory
            ->make(RegisterUserDataRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->auth->register(new RegisterUserData($data['name'], $data['email'], $data['password']));

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logOut();
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
