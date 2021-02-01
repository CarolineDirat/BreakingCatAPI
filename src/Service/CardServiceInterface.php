<?php

namespace App\Service;

interface CardServiceInterface
{
    /**
     * create.
     *
     * @param string               $imageContent
     * @param array<string,string> $quote        ["quote" : string, "author" : string]
     *
     * @return string
     */
    public function createContent(string $imageContent, array $quote): string;
}
