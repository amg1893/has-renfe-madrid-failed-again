<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorExceptionListener
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $this->logger->error((string) $exception);

        $response = new JsonResponse([
            'result' => false,
            'error' => 'There was an error'
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}