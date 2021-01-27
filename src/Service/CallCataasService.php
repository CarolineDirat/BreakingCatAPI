<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class CallCataasService extends CallApiService implements CallCataasServiceInterface
{
    const URL = 'https://cataas.com';

    /**
     * getRandomCat
     * Download the image in /public/img/cat.jpg.
     */
    public function getRandomCat(): ResponseInterface
    {
        // $result = file_put_contents(__DIR__ . '/../../public/img/cat.jpg', $response->getContent());
        return $this->getApi(self::URL, '/cat');
    }
}
