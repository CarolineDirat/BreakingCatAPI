<?php

namespace App\Tests\Service;

use App\Service\DeleteHomeCards;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;

final class DeleteHomeCardsTest extends WebTestCase
{
    public function testDeleteReturningDeletions(): void
    {
        // given
        $client = static::createClient();
        $client->request('GET', '/');
        $client->request('GET', '/new-home-card');
        $client->request('GET', '/');
        $deleteHomeCards = new DeleteHomeCards($client->getKernel());
        // when
        $deletions = $deleteHomeCards->delete();
        // then
        self::assertGreaterThan(0, $deletions);
    }

    public function testDeleteReturningZeroDeletion(): void
    {
        // given
        self::bootKernel();
        $container = static::getContainer();

        /** @var Kernel $kernel */
        $kernel = $container->get('kernel');
        $deleteHomeCards = new DeleteHomeCards($kernel);
        // when
        $deletions = $deleteHomeCards->delete();
        // then
        self::assertEquals(0, $deletions);
    }

    public function testToString(): void
    {
        // given
        self::bootKernel();
        $container = static::getContainer();

        /** @var Kernel $kernel */
        $kernel = $container->get('kernel');
        $deleteHomeCards = new DeleteHomeCards($kernel);
        // when
        $string = (string) $deleteHomeCards;
        // then
        self::assertEquals('DeleteHomeCards', $string);
    }
}
