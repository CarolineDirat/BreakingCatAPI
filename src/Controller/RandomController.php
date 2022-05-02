<?php

namespace App\Controller;

use App\Service\CallBreakingBadServiceInterface;
use App\Service\CallCataasServiceInterface;
use App\Service\CardServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
class RandomController extends AbstractController
{
    public function __construct(
        private CallBreakingBadServiceInterface $callBreakingBadService,
        private CallCataasServiceInterface $callCataasService,
        private CardServiceInterface $cardService,
        private KernelInterface $kernel,
    ) {
        $this->callBreakingBadService = $callBreakingBadService;
        $this->callCataasService = $callCataasService;
        $this->cardService = $cardService;
        $this->kernel = $kernel;
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
        $this->saveNewHomeCard($cardContent, $sessionId);

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
        $this->saveNewHomeCard($cardContent, $sessionId, $filename);

        return new JsonResponse([
            'sessionId' => $sessionId,
            'filename' => $filename],
        );
    }

    private function getCardContent(): ?string
    {
        $breakingBadArray = $this->callBreakingBadService->getRandomQuote();
        $cataas = (string) $this->callCataasService->getRandomCat()->getContent();

        return $this->cardService->createContent($cataas, $breakingBadArray);
    }

    private function saveNewHomeCard(
        ?string $cardContent,
        string $sessionId,
        string $filename = null
    ): void {
        if (empty($filename)) {
            $filename = 'home.jpeg';
        }
        $dir = sprintf(
            '%s/public/homeImage/%s',
            $this->kernel->getProjectDir(),
            $sessionId
        );
        $path = sprintf(
            '%s/%s',
            $dir,
            $filename
        );
        $filesystem = new Filesystem();
        if ($filesystem->exists($dir)) {
            $this->deleteOldCard($dir, $filesystem);
        }
        $filesystem->dumpFile($path, (string) $cardContent);
    }

    private function deleteOldCard(string $dir, Filesystem $filesystem): void
    {
        $files = (array) \scandir($dir);
        foreach ($files as $key => $file) {
            if (\str_contains((string) $file, 'home')) {
                $filesystem->remove($dir . '/' . $file);
            }
        }
    }
}
