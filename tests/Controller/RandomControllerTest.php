<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RandomControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/random/jpeg');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type', 'image/jpeg');
    }
}
