<?php

namespace App\Service;

final class CardService implements CardServiceInterface
{
    /**
     * card.
     *
     * @var resource
     */
    private $card;

    private string $font = __DIR__ . '/../../public/fonts/Averia_Serif_Libre.ttf';

    private int $imageWidth;

    private int $imageHeight;

    private ?int $numberOfLines = null;

    private int $backgroundColor;

    private int $textColor;

    public function createContent(string $imageContent, array $quote)
    {
        $this->createCardResource($imageContent, $quote);

        ob_start();
        \imagejpeg($this->card);
        $content = ob_get_clean();
        \imagedestroy($this->card);

        return $content ? $content : null;
    }

    /**
     * hydrate.
     *
     * @param resource $image
     * @param string   $text
     */
    public function hydrate($image, string $text): void
    {
        $this->imageHeight = \imagesy($image);
        $this->imageWidth = \imagesx($image);
        $this->numberOfLines = $this->computeNumberOfLines($text);
        $quoteHeigh = 60 + $this->numberOfLines * 25;

        /** @var resource $card */
        $card = \imagecreatetruecolor($this->imageWidth, $this->imageHeight + $quoteHeigh);
        $this->card = $card;
        $this->backgroundColor = (int) \imagecolorallocate($this->card, 0, 0, 0);
        $this->textColor = (int) \imagecolorallocate($this->card, 255, 255, 255);
    }

    /**
     * computeNumberOfLines.
     *
     * @param string $text Text of the Breaking bad quote
     */
    public function computeNumberOfLines(string $text): int
    {
        $charsArray = \str_split($text, 1);
        $charsTotalNb = \count($charsArray);
        $numberCharsPerLine = $this->computeNumberCharsPerLine();

        return (int) ceil($charsTotalNb / $numberCharsPerLine);
    }

    /**
     * defineAuthor.
     *
     * @param array<string, string> $quote
     *
     * @return string
     */
    public function defineAuthor(array $quote): string
    {
        $author = $quote['author'];

        return empty($author) ? 'Anonymous' : $author;
    }

    /**
     * Get card.
     *
     * @return false|resource
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Get the value of font.
     */
    public function getFont(): string
    {
        return $this->font;
    }

    /**
     * Get imageWidth.
     *
     * @return false|int
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * Get imageHeight.
     *
     * @return false|int
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * Get numberOfLines.
     *
     * @return null|int
     */
    public function getNumberOfLines()
    {
        return $this->numberOfLines;
    }

    /**
     * Get backgroundColor.
     *
     * @return false|int
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Get textColor.
     *
     * @return false|int
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * cardCreate.
     *
     * @param string                $imageContent
     * @param array<string, string> $quote
     */
    private function createCardResource(string $imageContent, array $quote): void
    {
        /** @var resource $image */
        $image = \imagecreatefromstring($imageContent);
        $text = $quote['quote'];
        $author = $this->defineAuthor($quote);

        $this->hydrate($image, $text);

        $this->prepareCard($image);

        \imagedestroy($image);

        $this->addTextToCard(
            $this->wrap($text)
        );
        $this->addAuthorToCard($author);
        $this->addFrameToCard();
    }

    /**
     * computeNumberCharsPerLine.
     *
     * @return int
     */
    private function computeNumberCharsPerLine(): int
    {
        return (int) floor(($this->imageWidth - 50) / 10);
    }

    /**
     * prepareCard.
     *
     * @param resource $image
     */
    private function prepareCard($image): void
    {
        \imagefill($this->card, 0, 0, $this->backgroundColor);
        \imagecopymerge($this->card, $image, 0, 0, 0, 0, $this->imageWidth, $this->imageHeight, 100);
    }

    /**
     * wrap.
     *
     * @param string $text
     *
     * @return string
     */
    private function wrap(string $text): string
    {
        return \wordwrap(
            $text,
            $this->computeNumberCharsPerLine(),
            "\n",
            \false
        );
    }

    /**
     * addText.
     *
     * @param string $textWrapped
     */
    private function addTextToCard(string $textWrapped): void
    {
        \imagettftext(
            $this->card,
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
     * addAuthor.
     *
     * @param string $author
     */
    private function addAuthorToCard(string $author): void
    {
        \imagettftext(
            $this->card,
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
     * addFrame.
     */
    private function addFrameToCard(): void
    {
        for ($i = 0; $i < 7; ++$i) {
            \imageline(
                $this->card,
                0 + $i,
                0 + $i,
                0 + $i,
                \imagesy($this->card),
                $this->backgroundColor
            );
            \imageline(
                $this->card,
                0,
                0 + $i,
                \imagesx($this->card),
                0 + $i,
                $this->backgroundColor
            );
            \imageline(
                $this->card,
                \imagesx($this->card) - $i,
                0,
                \imagesx($this->card) - $i,
                \imagesy($this->card),
                $this->backgroundColor
            );
        }
    }
}
