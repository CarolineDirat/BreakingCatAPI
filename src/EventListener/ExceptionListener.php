<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
                    'href' => '/api/ramdom-jpeg',
                    'method' => 'GET',
                ],
            ];

            $response = new JsonResponse($result, $result['error']['code'], []);

            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $event->setResponse($response);
        }
    }
}
