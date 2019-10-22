<?php

declare(strict_types=1);


namespace App\Repository;


use App\Entity\Landmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Landmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Landmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Landmark[]    findAll()
 * @method Landmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
}