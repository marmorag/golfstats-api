<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
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

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $manager->getRepository(User::class);
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
            return $this->invalidRequestResponse(array('message' => self::AUTH_MISSING_KEY));
        }

        $user = $this->repository->findOneBy(['email' => $login]);

        if (!isset($user) || !$user instanceof User) {
            return $this->unknownRessourceResponse(array('message' => 'The provided login does not exist.'));
        }

        if (!$this->encoder->isPasswordValid($user, $password)) {
            return $this->forbiddenResponse(array('message' => 'The provided password in invalid.'));
        }

        $data = array(
            'data' => array(
                'token' => $user->getApiToken()
            ),
        );

        return $this->successResponse($data);
    }
}
