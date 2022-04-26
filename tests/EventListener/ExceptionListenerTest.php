<?php

namespace App\Tests\EventListener;

use App\EventListener\ExceptionListener;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class ExceptionListenerTest extends TestCase
{
    public function testOnKernelExceptionInProdMode(): void
    {
        $kernel = new HttpKernel(new EventDispatcher(), new ControllerResolver());
        $event = new ExceptionEvent($kernel, new Request(), 1, new Exception('An Exception occurred !'));

        $exceptionListener = new ExceptionListener('prod');
        $exceptionListener->onKernelException($event);

        /** @var Response $response */
        $response = $event->getResponse();
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $statusCode = $response->getStatusCode();
        $this->assertTrue(400 <= $statusCode);

        $this->verifyResponseBody($response);
    }

    public function testOnKernelExceptionInDevMode(): void
    {
        $kernel = new HttpKernel(new EventDispatcher(), new ControllerResolver());
        $event = new ExceptionEvent($kernel, new Request(), 1, new Exception('An Exception occurred !'));

        $exceptionListener = new ExceptionListener('dev');
        $exceptionListener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertNull($response);
    }

    /**
     * @dataProvider provideStatusCodes
     */
    public function testOnKernelExceptionInProdModeWithHttpException(int $statusCode): void
    {
        $exceptionListener = new ExceptionListener('prod');

        $kernel = new HttpKernel(new EventDispatcher(), new ControllerResolver());
        $event = new ExceptionEvent($kernel, new Request(), 1, new HttpException($statusCode));

        $exceptionListener->onKernelException($event);

        /** @var Response $response */
        $response = $event->getResponse();
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $responseStatusCode = $response->getStatusCode();
        $this->assertTrue($statusCode === $responseStatusCode);

        $this->verifyResponseBody($response);
    }

    public function verifyResponseBody(Response $response): void
    {
        $serializer = new Serializer([], [new JsonEncoder()]);
        $body = $serializer->decode((string) $response->getContent(), 'json');

        $this->assertTrue(\array_key_exists('title', $body));
        $this->assertTrue(\array_key_exists('error', $body));
        $this->assertTrue(\array_key_exists('_links', $body));
        $this->assertSame('Oops! An Error Occurred.', $body['title']);
        $this->assertTrue(\array_key_exists('Get a random card', $body['_links']));
    }

    /**
     * provideStatusCodes.
     *
     * @return array<mixed>
     */
    public function provideStatusCodes(): array
    {
        $statusCodes = [];
        for ($i = 400; $i <= 418; ++$i) {
            $statusCodes[] = [$i];
        }
        $statusCodes[] = [422];
        $statusCodes[] = [425];
        $statusCodes[] = [426];
        $statusCodes[] = [428];
        $statusCodes[] = [429];
        $statusCodes[] = [431];
        $statusCodes[] = [451];
        for ($i = 500; $i <= 508; ++$i) {
            $statusCodes[] = [$i];
        }
        $statusCodes[] = [510];
        $statusCodes[] = [511];

        return $statusCodes;
    }
}
