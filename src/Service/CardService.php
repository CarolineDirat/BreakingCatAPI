<?php

namespace App\Service;

final class CardService implements CardServiceInterface
{
    /**
     * card.
     *
     * @var false|resource
     */
    private $card = false;

    private string $font = __DIR__ . '/../../public/fonts/Averia_Serif_Libre.ttf';

    /**
     * imageWidth.
     *
     * @var false|int
     */
    private $imageWidth = false;

    /**
     * imageHeight.
     *
     * @var false|int
     */
    private $imageHeight = false;

    /**
     * numberOfLines.
     *
     * @var null|int
     */
    private ?int $numberOfLines = null;

    /**
     * backgroundColor.
     *
     * @var false|int
     */
    private $backgroundColor = false;

    /**
     * textColor.
     *
     * @var false|int
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
        $this->createCardResource($imageContent, $quote);

        ob_start();
        \imagejpeg($this->card);
        $content = ob_get_clean();
        \imagedestroy($this->card);

        return $content;
    }

    /**
     * hydrate.
     *
     * @param resource $image
     * @param string   $text
     */
    private function hydrate($image, string $text): void
    {
        $this->imageHeight = \imagesy($image);
        $this->imageWidth = \imagesx($image);
        $this->numberOfLines = $this->computeNumberOfLines($text);
        $quoteHeigh = 60 + $this->numberOfLines * 25;
        $this->card = \imagecreatetruecolor($this->imageWidth, $this->imageHeight + $quoteHeigh);
        $this->backgroundColor = \imagecolorallocate($this->card, 0, 0, 0);
        $this->textColor = \imagecolorallocate($this->card, 255, 255, 255);
    }

    /**
     * cardCreate.
     *
     * @param string                $imageContent
     * @param array<string, string> $quote
     */
    private function createCardResource(string $imageContent, array $quote): void
    {
        $image = \imagecreatefromstring($imageContent);
        $text = $quote['quote'];
        $author = $this->defineAuthor($quote);

        $this->hydrate($image, $text);

        $this->prepareCard($image);

        \imagedestroy($image);

        $numberCharsPerLine = $this->computeNumberCharsPerLine();
        $textWrapped = \wordwrap($text, $numberCharsPerLine, "\n", \false);

        $this->addTextToCard($textWrapped);
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
     * computeNumberOfLines.
     *
     * @param string $text Text of the Breaking bad quote
     */
    private function computeNumberOfLines(string $text): int
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
    private function defineAuthor(array $quote): string
    {
        $author = $quote['author'];
        return empty($author) ? 'Anonymous' : $author;
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
