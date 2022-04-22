<?php

namespace App\Tests\Service;

use App\Service\CardService;
use PHPUnit\Framework\TestCase;

class CardServiceTest extends TestCase
{
    private string $imageContent;

    /**
     * @var resource
     */
    private $imageResource;

    public function setUp(): void
    {
        $this->imageContent = (string) \file_get_contents(__DIR__ . '/cat.jpg');

        /** @var resource $imageResource */
        $imageResource = \imagecreatefromstring($this->imageContent);
        $this->imageResource = $imageResource;
    }

    public function testCreateContentWithGoodArguments(): void
    {
        $cardService = new CardService();
        $quote = [
            'quote' => "You know how they say 'it's been a pleasure'? Well... it hasn't.",
            'author' => 'Mike Ehrmantraut',
        ];
        $this->assertIsString($cardService->createContent($this->imageContent, $quote));

        $quote['author'] = '';
        $this->assertIsString($cardService->createContent($this->imageContent, $quote));
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
        $cardService->createContent($imageContent, $quote);

/** @phpstan-ignore-line */
        $imageContent = null;
        $this->expectError();
        $cardService->createContent($imageContent, $quote); // @phpstan-ignore-line
    }

    public function testCreateContentWithWrongQuote(): void
    {
        $cardService = new CardService();
        $quote = ['coucou'];

        $this->expectError();
        $cardService->createContent($this->imageContent, $quote); // @phpstan-ignore-line
    }

    public function testDefineAuthorWithGoodQuoteArray(): void
    {
        $cardService = new CardService();

        $quote = [
            'author' => 'Mike Ehrmantraut',
        ];
        $this->assertEquals('Mike Ehrmantraut', $cardService->defineAuthor($quote));

        $quote = [
            'author' => '',
        ];
        $this->assertEquals('Anonymous', $cardService->defineAuthor($quote));
    }

    public function testDefineAuthorWithWrongQuote(): void
    {
        $cardService = new CardService();

        $quote = ['jfdlfkj' => 'fdqfqqf'];
        $this->expectError();
        $cardService->defineAuthor($quote);
    }

    /**
     * @dataProvider textProvider
     */
    public function testComputeNumberOfLines(string $text, int $result): void
    {
        $cardService = new CardService();
        $cardService->hydrate($this->imageResource, $text);

        $this->assertIsInt($cardService->computeNumberOfLines($text));
        $this->assertEquals($result, $cardService->computeNumberOfLines($text));
    }

    /**
     * textProvider.
     *
     * @return array<int, array<int, int|string>>
     */
    public function textProvider(): array
    {
        return [
            ["You know how they say \"it's been a pleasure\"? Well... it hasn't.", 2],
            ["When you have children, you always have family. They will always be your priority, 
                your responsibility. And a man, a man provides. And he does it even when he's not 
                appreciated or respected or even loved. He simply bears up and he does it. 
                Because he's a man.", 6],
            ['You don’t want a criminal lawyer. You want a criminal lawyer.', 2],
            ['So roll me further bitch!', 1],
        ];
    }

    public function testHydrate(): void
    {
        $text = 'You don’t want a criminal lawyer. You want a criminal lawyer.';
        $cardService = new CardService();
        $cardService->hydrate($this->imageResource, $text);
        $fontPathFromSrc = $cardService->getFont();
        $fontPathFromTests = \str_replace('src', 'tests', $fontPathFromSrc);

        $this->assertIsResource($cardService->getCard());
        $this->assertEquals(
            __DIR__ .'/../../public/fonts/Averia_Serif_Libre.ttf',
            $fontPathFromTests
        );
        $this->assertEquals(600, $cardService->getImageWidth());
        $this->assertEquals(450, $cardService->getImageHeight());
        $this->assertEquals(2, $cardService->getNumberOfLines());
        $this->assertEquals(0, $cardService->getBackgroundColor());
        $this->assertEquals(16777215, $cardService->getTextColor());
    }
}
