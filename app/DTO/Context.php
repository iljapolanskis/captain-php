<?php

namespace App\DTO;

use App\Api\Data\ContextInterface;
use App\Api\Data\UserInterface;
use Psr\Http\Message\RequestInterface;

class Context implements ContextInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function addData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function getRequest(): RequestInterface
    {
        return $this->get(self::REQUEST);
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function getUser(): ?UserInterface
    {
        return $this->get(self::USER);
    }

    public function getFeedPosts(): array
    {
        return $this->get(self::FEED_POSTS);
    }
}