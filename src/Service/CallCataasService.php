<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class CallCataasService extends CallApiService implements CallCataasServiceInterface
{
    public const URL = 'https://cataas.com';

    /**
     * getRandomCat
     * Download the image in /public/img/cat.jpg.
     *
     * @return ResponseInterface
     */
    public function getRandomCat(): ResponseInterface
    {
        return $this->getApi(self::URL, '/cat');
    }
}
