<?php

namespace App\Controller;

use App\Service\CallBreakingBadServiceInterface;
use App\Service\CallCataasServiceInterface;
use App\Service\CardServiceInterface;
use App\Service\SaveHomeCard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class RandomController extends AbstractController
{
    public function __construct(
        private CallBreakingBadServiceInterface $callBreakingBadService,
        private CallCataasServiceInterface $callCataasService,
        private CardServiceInterface $cardService,
        private SaveHomeCard $saveHomeCard
    ) {
        $this->callBreakingBadService = $callBreakingBadService;
        $this->callCataasService = $callCataasService;
        $this->cardService = $cardService;
        $this->saveHomeCard = $saveHomeCard;
    }

    /**
     * Returns a random jpeg card (cat picture with its Breaking Bad quote).
     *
     * @Route("/api/random-jpeg", name="api_random_jpeg")
     */
    public function jpeg(): Response
    {
        $cardContent = $this->getCardContent();

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
    public function home(SessionInterface $session): Response
    {
        $sessionId = Uuid::v4();
        $session->set('idUser', $sessionId);
        $cardContent = $this->getCardContent();
        $this->saveHomeCard->save($cardContent, $sessionId);

        return $this->render('homepage.html.twig', ['sessionId' => $sessionId]);
    }

    /**
     * @Route("/new-home-card", name="new_home_card")
     */
    public function newHomeCard(SessionInterface $session): JsonResponse
    {
        $sessionId = $session->get('idUser');
        $filename = 'home-' . \random_int(1, 9999) . '.jpeg';
        $cardContent = $this->getCardContent();
        $this->saveHomeCard->save($cardContent, $sessionId, $filename);

        return new JsonResponse(
            [
                'sessionId' => $sessionId,
                'filename' => $filename, ],
        );
    }

    private function getCardContent(): ?string
    {
        $breakingBadArray = $this->callBreakingBadService->getRandomQuote();
        $cataas = (string) $this->callCataasService->getRandomCat()->getContent();

        return $this->cardService->createContent($cataas, $breakingBadArray);
    }
}
