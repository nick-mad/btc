<?php

namespace App\Domain\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function get(string $url, array $options = []): ResponseInterface;
    public function post(string $url, array $options = []): ResponseInterface;
}
