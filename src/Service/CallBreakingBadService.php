<?php

namespace App\Service;

final class CallBreakingBadService extends CallApiService implements CallBreakingBadServiceInterface
{
    public const URL = 'https://breaking-bad-quotes.herokuapp.com/v1/quotes';

    /**
     * getRandomQuote from the Breaking Bad API Quotes.
     *
     * @return array<string, string> ["quote" => string, "author" => string ]
     */
    public function getRandomQuote(): array
    {
        try {
            $response = $this->getApi(self::URL)->toArray();
        } catch (\Throwable $e) {
            $message = 'Oops! An error occurred from Breaking Bad Quotes: ' . $e->getMessage();
            if (\str_contains($e->getMessage(), '503 Service Unavailable')) {
                $message = 'Sorry :( Service Unavailable from Breaking Bad Quotes';
            }
            $response = [
                [
                    'quote' => $message,
                    'author' => 'CaroCode',
                ],
            ];
        }

        return $response[0];
    }
}
