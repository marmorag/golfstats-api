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

    /**
     * @param int $statusCode
     * @param array<string, mixed>|null $transferData
     * @return JsonResponse
     */
    protected function buildResponse(int $statusCode, array $transferData = null): JsonResponse
    {
        $response = new JsonResponse(null, $statusCode);

        if (isset($transferData)) {
            $response->setContent(json_encode($transferData, JSON_THROW_ON_ERROR, 512));
        }

        return $response;
    }
}
