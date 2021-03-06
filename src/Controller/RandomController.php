<?php

namespace App\Controller;

use App\Service\CallBreakingBadServiceInterface;
use App\Service\CallCataasServiceInterface;
use App\Service\CardServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController extends AbstractController
{
    /**
     * Returns a random jpeg card (cat picture with its Breaking Bad quote).
     *
     * @Route("/api/random-jpeg", name="api_random_jpeg")
     */
    public function jpeg(
        CallBreakingBadServiceInterface $callBreakingBadService,
        CallCataasServiceInterface $callCataasService,
        CardServiceInterface $cardService
    ): Response {
        $breakingBadArray = $callBreakingBadService->getRandomQuote();

        $cataasResponse = $callCataasService->getRandomCat();

        $cardContent = $cardService->createContent($cataasResponse->getContent(), $breakingBadArray);

        return new Response(
            $cardContent,
            200,
            [
                'Content-Type' => 'image/jpeg',
            ]
        );
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('api_random_jpeg');
    }
}
