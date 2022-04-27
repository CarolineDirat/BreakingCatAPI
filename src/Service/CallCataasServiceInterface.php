<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface CallCataasServiceInterface
{
    /**
     * getRandomCat
     * Download the image in /public/img/cat.jpg.
     */
    public function getRandomCat(): Response;
}
