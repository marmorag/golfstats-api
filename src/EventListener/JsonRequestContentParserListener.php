<?php

namespace App\EventListener;

use App\Controller\AbstractApiController;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonRequestContentParserListener extends AbstractApiController {

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getContent() && in_array($request->getContentType(), ['json', 'application/json'])) {

            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->invalidRequestResponse('Invalid json body : ' . json_last_error_msg());
            }

            $request->request->replace(is_array($data) ? $data : array());
        }
        return null;
    }
}