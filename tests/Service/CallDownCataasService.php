<?php

namespace App\Tests\Service;

use App\Service\CallCataasService;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CallDownCataasService extends CallCataasService
{
    public function getApi(string $url, string $uri = ''): ResponseInterface
    {
        throw new \Exception($url . ': Cataas Service is down', 1);
    }
}
