<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RandomControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/random-jpeg');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type', 'image/jpeg');
    }

    public function testRedirection(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testNewCardHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/new-home-card');

        $this->assertResponseIsSuccessful();
    }
}
