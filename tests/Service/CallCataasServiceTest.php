<?php

namespace App\Tests\Service;

use App\Service\CallCataasService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

final class CallCataasServiceTest extends TestCase
{
    public function testGetRandomCat(): void
    {
        $client = HttpClient::create();
        $callCataasService = new CallCataasService($client);

        $this->assertEquals(200, $callCataasService->getRandomCat()->getStatusCode());
    }
}
