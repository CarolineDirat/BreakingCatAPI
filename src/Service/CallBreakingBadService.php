<?php

namespace App\Service;

final class CallBreakingBadService extends CallApiService implements CallBreakingBadServiceInterface
{
    const URL = 'https://breaking-bad-quotes.herokuapp.com/v1/quotes';

    /**
     * getRandomQuote from the Breaking Bad API Quotes.
     *
     * @return array<string, string> ["quote" => string, "author" => string ]
     */
    public function getRandomQuote(): array
    {
        $response = $this->getApi(self::URL)->toArray();

        return $response[0];
    }
}
