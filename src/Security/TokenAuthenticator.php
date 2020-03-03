<?php

namespace App\Security;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Service\TokenEncoderService;
use Lcobucci\JWT\Token;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private TokenEncoderService $encoder;
    private DenormalizerInterface $denormalizer;

    public function __construct(DenormalizerInterface $denormalizer, TokenEncoderService $encoder)
    {
        $this->encoder = $encoder;
        $this->denormalizer = $denormalizer;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => AbstractApiController::STATUS_401,
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request): Token
    {
        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            throw new AuthenticationException('Invalid token : missing X-AUTH-TOKEN header.');
        }
        // What you return here will be passed to getUser() as $credentials
        try {
            return $this->encoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            throw new \UnexpectedValueException($e->getMessage());
        }
    }

    /**
     * @param Token $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return User|null
     * @throws ExceptionInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $userClaim = $credentials->getClaim('user');

        if ($userClaim === null) {
            throw new AuthenticationException('Invalid token : unable to retrieve user claim in given token');
        }

        /** @var User|null $user */
        $user = $this->denormalizer->denormalize($userClaim, User::class, 'token');

        if ($user === null) {
            throw new AuthenticationException('Invalid token : the given user does not exists.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => AbstractApiController::STATUS_403
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
