<?php

namespace App\Tests\Service;

use App\Service\CardService;
use PHPUnit\Framework\TestCase;

class CardServiceTest extends TestCase
{
    public function testCreateContentWithGoodArguments(): void
    {
        $cardService = new CardService();
        $imageContent = \file_get_contents(__DIR__ . '/cat.jpg');
        $quote = [
            'quote' => "You know how they say 'it's been a pleasure'? Well... it hasn't.",
            'author' => 'Mike Ehrmantraut',
        ];
        $this->assertIsString($cardService->createContent($imageContent, $quote));

        $quote['author'] = '';
        $this->assertIsString($cardService->createContent($imageContent, $quote));
    }

    public function testCreateContentWithWrongImageContent(): void
    {
        $cardService = new CardService();
        $imageContent = 1;
        $quote = [
            'quote' => "You know how they say 'it's been a pleasure'? Well... it hasn't.",
            'author' => 'Mike Ehrmantraut',
        ];

        $this->expectError();
        $cardService->createContent($imageContent, $quote); /** @phpstan-ignore-line */
        $imageContent = null;
        $this->expectError();
        $cardService->createContent($imageContent, $quote); // @phpstan-ignore-line
    }

    public function testCreateContentWithWrongQuote(): void
    {
        $cardService = new CardService();
        $imageContent = \file_get_contents(__DIR__ . '/cat.jpg');
        $quote = ['coucou'];

        $this->expectError();
        $cardService->createContent($imageContent, $quote); // @phpstan-ignore-line
    }
}
