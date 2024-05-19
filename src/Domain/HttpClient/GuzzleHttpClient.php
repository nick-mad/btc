<?php

namespace App\Domain\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements ClientInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(string $url, array $options = []): ResponseInterface
    {
        return $this->client->request('GET', $url, $options);
    }

    public function post(string $url, array $options = []): ResponseInterface
    {
        return $this->client->request('POST', $url, $options);
    }
}
