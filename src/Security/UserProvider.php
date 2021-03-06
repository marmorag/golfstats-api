<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->repository->findOneBy(['mail' => $username]);

        if (!isset($user) || !$user instanceof User) {
            throw new UsernameNotFoundException('The given login was not found');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): ?UserInterface
    {
        // Not called because of API implementation
        return null;
    }
}
