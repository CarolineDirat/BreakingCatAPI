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

        $cataas = (string) $callCataasService->getRandomCat()->getContent();

        $cardContent = $cardService->createContent($cataas, $breakingBadArray);

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
    public function home(): Response
    {
        return $this->render('homepage.html.twig');
    }
}
