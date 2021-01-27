<?php

namespace App\Tests\Service;

use App\Service\CallBreakingBadService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

final class CallBreakingBadServiceTest extends TestCase
{
    public function testGetRandomQuote(): void
    {
        $client = HttpClient::create();
        $callBreakingBadService = new CallBreakingBadService($client);

        $breakingBadArray = $callBreakingBadService->getRandomQuote();

        $this->assertArrayHasKey("quote", $breakingBadArray);
        $this->assertArrayHasKey("author", $breakingBadArray);
    }
}
