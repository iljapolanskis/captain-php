<?php

namespace App\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CurlController
{
    public function get(Request $request, Response $response): Response
    {
        $handle = curl_init('https://jsonplaceholder.typicode.com/todos/1');

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($handle);

        curl_close($handle);

        $response->getBody()->write($body);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
