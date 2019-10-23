<?php

namespace App\EventListener;


use App\Controller\AbstractApiController;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class MethodNotAllowedExceptionListener extends AbstractApiController
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        // Only handle nethod not allowed exception
        if ($exception instanceof MethodNotAllowedHttpException){
            $response = $this->forbiddenMethodResponse();
            $event->setResponse($response);
        }
    }
}