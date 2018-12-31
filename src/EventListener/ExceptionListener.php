<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\EventListener;

use App\Enum\HttpCodeEnum;
use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($exception instanceof NotFoundHttpException) {
            $response = new Response();
            $response->setContent(json_encode([
                'error' => 'Entity not found'
            ]));

            $response->setStatusCode(HttpCodeEnum::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }
}
