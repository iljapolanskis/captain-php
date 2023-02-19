<?php

namespace App\Controller;

use App\Model\PubNubClient;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PubNubController
{
    public function __construct(
        private PubNubClient $pubNubClient,
        private EntityManager $entityManager,
    ) {}

    public function publish(Request $request, Response $response): Response
    {
        $database = $this->entityManager->getConnection()->getDatabase() ?? 'default';
        $this->pubNubClient->publishToNewsChannel($database);
        return $response->withStatus(200);
    }
}
