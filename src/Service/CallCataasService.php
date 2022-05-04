<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class CallCataasService extends CallApiService implements CallCataasServiceInterface
{
    public const URL = 'https://cataas.com';
    private const PATH503CAT = __DIR__ . '/503.jpg';

    public function getRandomCat(): Response
    {
        try {
            $response = $this->getApi(self::URL, '/cat')->getContent();
        } catch (\Throwable $th) {
            $response = (string) \file_get_contents(self::PATH503CAT);
        }

        return new Response($response);
    }
}
