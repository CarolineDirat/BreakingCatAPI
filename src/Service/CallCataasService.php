<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

final class CallCataasService extends CallApiService implements CallCataasServiceInterface
{
    public const URL = 'https://cataas.com';
    private const path503cat = __DIR__ . '/503.jpg';

    public function getRandomCat(): Response
    {
        try {
            $response = $this->getApi(self::URL, '/cat')->getContent();

            throw new \Exception('Cataas is Down', 1);
        } catch (\Throwable $th) {
            $response = (string) \file_get_contents(self::path503cat);
        }

        return new Response($response);
    }
}
