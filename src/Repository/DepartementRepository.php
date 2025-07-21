<?php

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    public function findByNh($nh): array
    {
        return $this->createQueryBuilder('d')
            ->join('n.niveauHierarchiques', 'n')
            ->andWhere('n.id = :idNiveau')
            ->setParameter('idNiveau', $nh->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
