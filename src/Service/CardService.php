<?php

namespace App\Service;

final class CardService implements CardServiceInterface
{            
    /**
     * card
     *
     * @var resource|null
     */
    private $card = null;

    private string $font = __DIR__ . '/../../public/fonts/Averia_Serif_Libre.ttf';
    
    /**
     * imageWidth
     *
     * @var int|false
     */
    private $imageWidth = false;
    
    /**
     * imageHeight
     *
     * @var int|false
     */
    private $imageHeight = false;
    
    /**
     * numberOfLines
     *
     * @var int|null
     */
    private ?int $numberOfLines = null;

    
    /**
     * backgroundColor
     *
     * @var int|false
     */
    private $backgroundColor = false;
    
    /**
     * textColor
     *
     * @var int|false
     */
    private $textColor = false;

    /**
     * create.
     *
     * @param string               $imageContent
     * @param array<string,string> $quote        ["quote" : string, "author" : string]
     *
     * @return string
     */
    public function createContent(string $imageContent, array $quote): string
    {
        $card = $this->createResource($imageContent, $quote);

        ob_start();
        \imagejpeg($card);
        $content = ob_get_clean();
        \imagedestroy($card);

        return $content;
    }

    /**
     * cardCreate.
     *
     * @param string                $imageContent
     * @param array<string, string> $quote
     *
     * @return false|resource
     */
    private function createResource(string $imageContent, array $quote)
    {
        $image = \imagecreatefromstring($imageContent);
        $this->imageHeight = \imagesy($image);
        $this->imageWidth = \imagesx($image);
        $numberCharsPerLine = $this->computeNumberCharsPerLine();

        $text = $quote['quote'];
        $author = $this->defineAuthor($quote);

        $this->numberOfLines = $this->computeNumberOfLines($text);
        $quoteHeigh = 60 + $this->numberOfLines * 25;

        $textWrapped = \wordwrap($text, $numberCharsPerLine, "\n", \false);

        $card = \imagecreatetruecolor($this->imageWidth, $this->imageHeight + $quoteHeigh);
        $this->prepareCard($card, $image);

        \imagedestroy($image);

        $this->addText($card, $textWrapped);
        $this->addAuthor($card, $author);
        $this->addFrame($card);

        return $card;
    }
    
    /**
     * computeNumberCharsPerLine
     *
     * @return int
     */
    private function computeNumberCharsPerLine(): int
    {        
        return (int) floor(($this->imageWidth - 50) / 10);
    }
    
    /**
     * computeNumberOfLines
     *
     * @param  string $text Text of the Breaking bad quote
     */
    private function computeNumberOfLines(string $text): int
    {
        $charsArray = \str_split($text, 1);
        $charsTotalNb = \count($charsArray);
        $numberCharsPerLine = $this->computeNumberCharsPerLine();
        return (int) ceil($charsTotalNb / $numberCharsPerLine);
    }
    
    /**
     * defineAuthor
     *
     * @param  array<string, string> $quote
     * 
     * @return string
     */
    private function defineAuthor(array $quote): string
    {
        return empty($author) ? 'Anonymous' : $quote['author'];
    }
    
    /**
     * prepareCard
     *
     * @param  resource $card
     * @param  resource $image
     */
    private function prepareCard($card, $image): void
    {
        $this->backgroundColor = \imagecolorallocate($card, 0, 0, 0);
        $this->textColor = \imagecolorallocate($card, 255, 255, 255);

        \imagefill($card, 0, 0, $this->backgroundColor);
        \imagecopymerge($card, $image, 0, 0, 0, 0, $this->imageWidth, $this->imageHeight, 100);
    }
    
    /**
     * addText
     *
     * @param resource $card
     * @param string $textWrapped
     * 
     */
    private function addText($card, string $textWrapped): void
    {
        \imagettftext(
            $card,
            15,
            0,
            25,
            $this->imageHeight + 30,
            $this->textColor,
            $this->font,
            $textWrapped
        );
    }
    
    /**
     * addAuthor
     *
     * @param  resource $card
     * @param  string $author
     */
    private function addAuthor($card, string $author): void
    {
        \imagettftext(
            $card,
            12,
            0,
            (int) $this->imageWidth / 2,
            $this->imageHeight + 40 + $this->numberOfLines * 25,
            $this->textColor,
            $this->font,
            '-' . $author . '-'
        );
    }
    
    /**
     * addFrame
     *
     * @param  resource $card
     * 
     * @return void
     */
    private function addFrame($card): void
    {
        for ($i = 0; $i < 7; ++$i) {
            \imageline($card, 0 + $i, 0 + $i, 0 + $i, \imagesy($card), $this->backgroundColor);
            \imageline($card, 0, 0 + $i, \imagesx($card), 0 + $i, $this->backgroundColor);
            \imageline($card, \imagesx($card) - $i, 0, \imagesx($card) - $i, \imagesy($card), $this->backgroundColor);
        }
    }
}
