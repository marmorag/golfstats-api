<?php

namespace App\EventListener;

use App\Controller\AbstractApiController;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonRequestContentParserListener extends AbstractApiController
{
    public function onKernelRequest(ResponseEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getContent() && in_array($request->getContentType(), ['json', 'application/json'], true)) {
            try {
                $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $exception) {
                throw new BadRequestHttpException('Invalid json body : ' . json_last_error_msg());
            }

            $request->request->replace(is_array($data) ? $data : array());
        }
    }
}
