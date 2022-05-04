<?php

namespace App\Tests\Service;

use App\Service\CallBreakingBadService;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CallDownBreakingBadService extends CallBreakingBadService
{
    public function getApi(string $url, string $uri = ''): ResponseInterface
    {
        throw new \Exception($url . ': 503 Service Unavailable - Breaking Bad Quotes Service is down', 1);
    }
}
