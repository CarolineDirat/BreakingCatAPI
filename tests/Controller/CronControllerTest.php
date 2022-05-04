<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CronControllerTest extends WebTestCase
{
    public function testDeleteHomeCards(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cron/delete-homecards');

        $this->assertResponseIsSuccessful();
    }
}
