<?php

namespace App\Api\Data;

use Psr\Http\Message\RequestInterface;

interface ContextInterface
{
    public const REQUEST = 'request';
    public const USER = 'user';
    public const FEED_POSTS = 'feed_posts';

    public function get(string $key): mixed;

    public function getData(): array;

    public function setData(array $data): void;

    public function addData(string $key, mixed $value): void;

    public function getRequest(): RequestInterface;

    public function getUser(): ?UserInterface;

    public function getFeedPosts(): array;
}