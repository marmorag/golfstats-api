<?php

declare(strict_types=1);


namespace App\Repository;


use App\Entity\Contact;
use App\Entity\Scorecard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Scorecard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scorecard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scorecard[]    findAll()
 * @method Scorecard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScorecardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
}