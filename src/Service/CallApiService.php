<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CallApiService
{
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    protected function getApi(string $url, string $uri = ''): ResponseInterface
    {
        return $this->client->request('GET', $url . $uri);
    }
}
