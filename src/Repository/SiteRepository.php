<?php

namespace App\Repository;

use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Site>
 */
class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

  
    public function getAll(): array
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findActiveAndAvailable(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.isActive = :active')
            ->andWhere('s.isIndisponible = :false OR s.isIndisponible IS NULL')
            ->setParameter('active', true)
            ->setParameter('false', false)
            ->getQuery()
            ->getResult();
    }

  
}
