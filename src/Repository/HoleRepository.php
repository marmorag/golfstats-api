<?php

declare(strict_types=1);


namespace App\Repository;

use App\Entity\Hole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hole[]    findAll()
 * @method Hole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Hole::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
