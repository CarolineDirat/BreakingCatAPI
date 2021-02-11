<?php

namespace App\Service;

interface CallBreakingBadServiceInterface
{
    /**
     * getRandomQuote from the Breaking Bad API Quotes.
     *
     * @return array<string, string> ["quote" => string, "author" => string ]
     */
    public function getRandomQuote(): array;
}
