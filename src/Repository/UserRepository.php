<?php

namespace App\Repository;

use App\Controller\Api\UserController;
use App\Entity\User;
use App\Exception\DenormalizationException;
use App\Exception\InvalidUserException;
use App\Exception\UserAlreadyExistEception;
use App\Service\ObjectSerializerGetter;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;


class UserRepository extends DocumentRepository
{

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * TODO investigate on repository with dependancies
     * https://www.doctrine-project.org/projects/doctrine-mongodb-odm/en/1.2/reference/document-repositories.html#repositories-with-additional-dependencies
     * https://www.doctrine-project.org/projects/doctrine-mongodb-bundle/en/3.5/config.html
     *
     * Because of unable to register custom RepositoryFactory and use it, the job is done here
     */
    public function constructorOperation()
    {
        if (!isset($this->serializer)){
            $this->serializer = ObjectSerializerGetter::getSerializer();
        }
    }

    /**
     * Create and register a new User from a json serialized one.
     *
     * @param $rawUser
     * @param UserPasswordEncoderInterface $encoder
     * @return User
     *
     * @throws DenormalizationException
     */
    public function createUser($rawUser, UserPasswordEncoderInterface $encoder) : User
    {
        $this->constructorOperation();

        try {
            $user = $this->serializer->denormalize($rawUser, User::class);
        } catch (ExceptionInterface $e) {
            throw new DenormalizationException('Unable to denormalize the given array : ' . $e->getMessage());
        }

        if (!$user instanceof User){
            throw new DenormalizationException(UserController::RESPONSE_USER_DENORMALIZATION_FAILED);
        }

        $encoded = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);

        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }

    /**
     * @param $jsonUser
     * @throws UserAlreadyExistEception
     * @throws InvalidUserException
     */
    public function checkUserAlreadyExist($jsonUser): void
    {
        if (!isset($jsonUser['email'])){
            throw new InvalidUserException('Missing email entry.');
        }

        if ($this->findOneBy(['email' => $jsonUser['email']])){
            throw new UserAlreadyExistEception(UserController::RESPONSE_KEY_ALREADY_EXIST);
        }
    }

}