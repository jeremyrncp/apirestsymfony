<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ApiExceptionInterface) {
            $response = new Response();
            $response->setContent(json_encode([
                "error" => $exception->getMessage()
            ]));

            $response->setStatusCode($exception->getCode());

            $event->setResponse($response);
        }
    }
}
