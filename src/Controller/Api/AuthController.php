<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TokenEncoderService;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends AbstractApiController
{
    public const AUTH_MISSING_KEY = 'Some parameters are missing. The request must provide login and password.';
    public const AUTH_NOT_FOUND_USER = 'The provided login does not exist.';
    public const AUTH_INVALID_PASSWORD = 'The provided password is invalid.';

    private UserRepository $repository;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    /**
     * @Route(path="/api/auth", name="api_authenticate", methods={"POST"})
     *
     * @param Request $request
     * @param TokenEncoderService $encoderService
     * @param NormalizerInterface $normalizer
     *
     * @return Response
     * @throws ExceptionInterface
     * @throws JWTEncodeFailureException
     */
    public function authenticate(Request $request, TokenEncoderService $encoderService, NormalizerInterface $normalizer): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if (!isset($username, $password)) {
            throw new BadRequestHttpException(static::AUTH_MISSING_KEY);
        }

        $user = $this->repository->findOneBy(['email' => $username]);

        if (!isset($user) || !$user instanceof User) {
            throw new NotFoundHttpException(static::AUTH_NOT_FOUND_USER);
        }

        if (!$this->encoder->isPasswordValid($user, $password)) {
            throw new AccessDeniedHttpException(static::AUTH_INVALID_PASSWORD);
        }

        $data = [
            'data' => [
                'token' => $encoderService->encode(['user' => $normalizer->normalize($user)]),
            ],
        ];

        return $this->buildResponse(Response::HTTP_OK, $data);
    }
}
