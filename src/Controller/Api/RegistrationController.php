<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Exception\DenormalizationException;
use App\Exception\InvalidUserException;
use App\Exception\UserAlreadyExistEception;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractApiController
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * RegistrationController constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param DocumentManager $manager
     */
    public function __construct(UserPasswordEncoderInterface $encoder, DocumentManager $manager)
    {
        $this->repository = $manager->getRepository(User::class);
        $this->encoder = $encoder;
    }

    /**
     * @Route(path="/register", name="registration:register", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request): Response
    {
        $rawUser = json_decode($request->request->get('user'), true);

        if (!isset($rawUser)){
            // TODO : more precise message code
            return $this->invalidRequestResponse();
        }

        try {
            $this->repository->checkUserAlreadyExist($rawUser);
            $user = $this->repository->createUser($rawUser, $this->encoder);
        } catch (DenormalizationException $e) {
            return $this->internalFailureResponse(array('message' => $e->getMessage()));
        } catch (UserAlreadyExistEception|InvalidUserException $e) {
            return $this->invalidRequestResponse(array('message' => $e->getMessage()));
        }

        $data = array(
            'data' => array(
                'token' => $user->getApiToken()
            ),
        );

        return $this->createdResponse($data);
    }

}