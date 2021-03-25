<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionListener
{
    /**
     * environment = value of APP_ENV.
     *
     * @var string
     */
    private string $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * onKernelException.
     *
     * The response will be a json response the error occurred
     *
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if ('prod' === $this->environment) {
            $exception = $event->getThrowable();
            $result = $this->makeErrorArray($exception);
            $response = new JsonResponse($result, $result['error']['code'], []);

            $statusCode = ($exception instanceof HttpExceptionInterface)
                ? $exception->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR
            ;
            $response->setStatusCode($statusCode);

            $event->setResponse($response);
        }
    }

    /**
     * makeErrorArray.
     *
     * @param Throwable $exception
     *
     * @return array<mixed>
     */
    private function makeErrorArray(Throwable $exception): array
    {
        $result['title'] = 'Oops! An Error Occurred.';
        $code = $exception->getCode();
        $code = empty($code) ? Response::HTTP_BAD_REQUEST : $code;
        $result['error'] = [
            'code' => $code,
            'text' => Response::$statusTexts[$code],
            'message' => $exception->getMessage(),
        ];
        $result['_links'] = [
            'Get a random card' => [
                'href' => '/api/random-jpeg',
                'method' => 'GET',
            ],
        ];

        return $result;
    }
}
