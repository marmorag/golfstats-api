<?php

namespace App\Security;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TokenEncoderService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Serializer\SerializerInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private TokenEncoderService $encoder;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, TokenEncoderService $encoder)
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => AbstractApiController::STATUS_401,
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            // No token?
            $token = null;
        }
        // What you return here will be passed to getUser() as $credentials
        try {
            return $this->encoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            throw new \UnexpectedValueException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $userClaim = $credentials->getClaim('user');

        if ($userClaim === null) {
            throw new AuthenticationException('Invalid token : unable to retrieve user claim in given token');
        }

        /** @var User|null $user */
        $user = $this->serializer->deserialize($userClaim, User::class, 'json');

        if ($user === null) {
            throw new AuthenticationException('Invalid token : the given user does not exists.');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => AbstractApiController::STATUS_403
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
