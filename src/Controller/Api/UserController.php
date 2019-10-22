<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Exception\DenormalizationException;
use App\Exception\InvalidUserException;
use App\Exception\UserAlreadyExistEception;
use App\Repository\UserRepository;
use App\Service\ObjectSerializerGetter;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller\Api
 *
 * REST controller.
 * Support GET, POST, DELETE, PATCH
 */
class UserController extends AbstractApiController
{

    public const RESPONSE_USER_CREATED = 'Successfully created User ressource.';
    public const RESPONSE_USER_NOT_FOUND = 'The requested user was not found.';
    public const RESPONSE_USER_DENORMALIZATION_FAILED = 'User ressource deserialization failed.';
    public const RESPONSE_KEY_ALREADY_EXIST = 'The provided email already exist. Please use another.';
    public const RESPONSE_BAD_REQUEST_MISSING_KEY = 'A key component is missing in provided data.';

    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(DocumentManager $dm, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $dm->getRepository(User::class);
        $this->serializer = ObjectSerializerGetter::getSerializer();
        $this->encoder = $encoder;
    }

    /**
     * @Route("/users", name="users:getCollection", methods={"GET"})
     * @Route("/users/page/{page}", name="users:getPaginatedCollection", methods={"GET"}, requirements={"page"="\d+"})
     *
     * @param int $page
     *
     * @return Response
     */
    public function apiGetUsers($page = 1): Response
    {
        $users = $this->userRepository->findAll();

        $jsonUsers = $this->serializer->serialize($users, 'json', ['ignored_attributes' => ['password', 'apiToken']]);

        $data = array(
            'data' => $jsonUsers,
            'meta' => array(
                'count' => count($users),
                'type' => 'Collection:User',
            ),
        );

        return $this->successResponse($data);
    }

    /**
     * @Route("/users/{id}", name="users:getOne", methods={"GET"}, requirements={"id"="\S+"})
     *
     * @param string $id
     *
     * @return Response
     *
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function apiGetUser(string $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!isset($user)){
            return $this->unknownRessourceResponse(self::RESPONSE_USER_NOT_FOUND);
        }

        $jsonUser = $this->serializer->serialize($user, 'json', ['ignored_attributes' => ['password', 'apiToken']]);

        $data = array(
            'data' => $jsonUser,
            'meta' => array(
                'type' => 'User'
            ),
        );

        return $this->successResponse($data);
    }

    /**
     * @Route("/users", name="users:create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function apiCreateUser(Request $request): Response
    {
        $rawUser = $request->request->get('user');

        if (!isset($rawUser)){
            // TODO : more precise message code
            return $this->invalidRequestResponse(self::RESPONSE_BAD_REQUEST_MISSING_KEY);
        }

        try {
            $this->userRepository->checkUserAlreadyExist($rawUser);
            $user = $this->userRepository->createUser($rawUser, $this->encoder);
        } catch (DenormalizationException $e) {
            // 500
            return $this->internalFailureResponse($e->getMessage());
        } catch (UserAlreadyExistEception|InvalidUserException $e) {
            // 400
            return $this->invalidRequestResponse(array('message' => $e->getMessage()));
        }

        $data = array(
            'message' => self::RESPONSE_USER_CREATED,
            'data' => array(
                'id' => $user->getId(),
                'link' => array(
                    'rel' => 'self',
                    'href' => $this->generateUrl('users:getOne', ['id' => $user->getId()]),
                ),
            ),
            'meta' => array(
                'type' => 'User::meta'
            ),
        );

        return $this->createdResponse($data);
    }
}
