<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends AbstractApiController
{
    public const AUTH_MISSING_KEY = 'Some parameters are missing. The request must provide login and password.';
    public const AUTH_NOT_FOUND_USER = 'The provided login does not exist.';
    public const AUTH_INVALID_PASSWORD = 'The provided password in invalid.';

    private UserRepository $repository;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    /**
     * @Route(path="/auth", name="auth:authenticate", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request): Response
    {
        $login = $request->request->get('login');
        $password = $request->request->get('password');

        if (!isset($login, $password)) {
            throw new BadRequestHttpException(static::AUTH_MISSING_KEY);
        }

        $user = $this->repository->findOneBy(['email' => $login]);

        if (!isset($user) || !$user instanceof User) {
            throw new NotFoundHttpException(static::AUTH_NOT_FOUND_USER);
        }

        if (!$this->encoder->isPasswordValid($user, $password)) {
            throw new AccessDeniedHttpException(static::AUTH_INVALID_PASSWORD);
        }

        $data = [
            'data' => [
                'token' => $user->getApiToken()
            ],
        ];

        return $this->buildResponse(Response::HTTP_OK, $data);
    }
}
