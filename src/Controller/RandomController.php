<?php

namespace App\Controller;

use App\Service\CallBreakingBadServiceInterface;
use App\Service\CallCataasServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController extends AbstractController
{
    /**
     * @Route("/api/random/jpeg", name="api_random_jpeg")
     */
    public function index(
        CallBreakingBadServiceInterface $callBreakingBadService,
        CallCataasServiceInterface $callCataasService
    ): Response {
        
        $breakingBadArray = $callBreakingBadService->getRandomQuote();

        $cataasResponse = $callCataasService->getRandomCat();

        // return by default (the implementation is not finished)
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RandomController.php',
        ]);
    }
}
