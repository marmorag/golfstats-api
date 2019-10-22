<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends AbstractController
{

    public const STATUS_200 = 'The request was successfully completed.';
    public const STATUS_201 = 'A new resource was successfully created.';
    public const STATUS_204 = 'No data to sent back.';
    public const STATUS_400 = 'The request was invalid.';
    public const STATUS_401 = 'The request did not include an authentication token or the authentication token was expired.';
    public const STATUS_403 = 'The client did not have permission to access the requested resource.';
    public const STATUS_404 = 'The requested ressource was not found.';
    public const STATUS_405 = 'The HTTP method in the request was not supported by the resource.';
    public const STATUS_500 = 'The request was not completed due to an internal error on the server side.';
    public const STATUS_503 = 'An unknown error occured.';

    private function buildResponse($statusCode, $transferData): JsonResponse
    {
        $response = new JsonResponse();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setStatusCode($statusCode);

        if (isset($transferData)){
            $response->setContent(json_encode($transferData));
        }

        return $response;
    }

    /**
     * Standard success return code
     *
     * @param $data
     * @return Response
     */
    public function successResponse($data = null): Response
    {
        $transferData = $data ?? array('message' => 'Empty message.');

        return $this->buildResponse(Response::HTTP_OK, $transferData);
    }

    public function createdResponse($data = null): JsonResponse
    {
        $transferData = $data ?? array('message' => 'Empty message.');

        return $this->buildResponse(Response::HTTP_CREATED, $transferData);
    }

    public function noContentResponse(): JsonResponse
    {
        return $this->buildResponse(Response::HTTP_NO_CONTENT, null);
    }

    public function invalidRequestResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_400);
        return $this->buildResponse(Response::HTTP_BAD_REQUEST, $testedMessage);
    }

    public function unauthorizedReponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_401);
        return $this->buildResponse(Response::HTTP_UNAUTHORIZED, $testedMessage);
    }

    public function forbiddenResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_403);
        return $this->buildResponse(Response::HTTP_FORBIDDEN, $testedMessage);
    }

    public function unknownRessourceResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_404);
        return $this->buildResponse(Response::HTTP_NOT_FOUND, $testedMessage);
    }

    public function forbiddenMethodResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_405);
        return $this->buildResponse(Response::HTTP_METHOD_NOT_ALLOWED, $testedMessage);
    }

    public function internalFailureResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_500);
        return $this->buildResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $testedMessage);
    }

    public function unknownErrorResponse($message = null): JsonResponse
    {
        $testedMessage = $message ?? array('message' => self::STATUS_503);
        return $this->buildResponse(Response::HTTP_SERVICE_UNAVAILABLE, $testedMessage);
    }
}