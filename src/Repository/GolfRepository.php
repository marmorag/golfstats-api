<?php

namespace App\Repository;


use App\Entity\Golf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Golf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Golf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Golf[]    findAll()
 * @method Golf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GolfRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * Check if the given course already exist or not
     *
     * @param Golf $golf
     *
     * @return bool
     */
    public function exist($golf): bool
    {
        if ($this->findOneBy(['name' => $golf->getName()]))
            return true;

        if ($golf->getId() === null)
            return false;

        if ($this->findOneBy(['id' => $golf->getId()]))
            return true;

        return false;
    }
}