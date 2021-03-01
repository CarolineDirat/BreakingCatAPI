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
        $heigh = \imagesy($image);
        $width = \imagesx($image);

        $text = $quote['quote'];

        $charsNbPerLine = $this->computeCharsNbPerLine($width);
        $linesNumber = $this->computeLinesNumber($text, $width);
        $quoteHeigh = $this->computeQuoteHeight($linesNumber);

        $textWrapped = \wordwrap($text, $charsNbPerLine, "\n", \false);

        $card = \imagecreatetruecolor($width, $heigh + $quoteHeigh);
        $black = \imagecolorallocate($card, 0, 0, 0);
        $white = \imagecolorallocate($card, 255, 255, 255);
        
        \imagefill($card, 0, 0, $black);
        \imagecopymerge($card, $image, 0, 0, 0, 0, $width, $heigh, 100);

        \imagedestroy($image);

        $font = __DIR__ . '/../../public/fonts/Averia_Serif_Libre.ttf';

        \imagettftext($card, 15, 0, 25, $heigh + 30, $white, $font, $textWrapped);

        \imagettftext(
            $card,
            12,
            0,
            (int) $width / 2,
            $heigh + 40 + $linesNumber * 25,
            $white,
            $font,
            '-' . $this->defineAuthor($quote) . '-'
        );

        $this->addFrame($card, $black);

        return $card;
    }
    
    /**
     * computeCharsNbPerLine
     *
     * @param  int $width The width of the cat's picture
     * 
     * @return int
     */
    private function computeCharsNbPerLine(int $width): int
    {        
        return (int) floor(($width - 50) / 10);
    }
    
    /**
     * computeLinesNumber
     *
     * @param  string $text Text of the Breaking bad quote
     * @param  int $width The width of the cat's picture
     * 
     * @return int number of lines to write the quote text
     */
    private function computeLinesNumber(string $text, int $width): int
    {
        $charsArray = \str_split($text, 1);
        $charsTotalNb = \count($charsArray);
        $charsNbPerLine = $this->computeCharsNbPerLine($width);
        
        return (int) ceil($charsTotalNb / $charsNbPerLine);
    }
        
    /**
     * computeQuoteHeight
     *
     * @param  int $linesNumber Number of lines to write the text under the cat picture
     * 
     * @return int
     */
    private function computeQuoteHeight(int $linesNumber): int
    {
        return 60 + $linesNumber * 25;
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
     * addFrame
     *
     * @param  resource $card
     * @param  int $color
     * 
     * @return void
     */
    private function addFrame($card, int $color): void
    {
        for ($i = 0; $i < 7; ++$i) {
            \imageline($card, 0 + $i, 0 + $i, 0 + $i, \imagesy($card), $color);
            \imageline($card, 0, 0 + $i, \imagesx($card), 0 + $i, $color);
            \imageline($card, \imagesx($card) - $i, 0, \imagesx($card) - $i, \imagesy($card), $color);
        }
    }
}
