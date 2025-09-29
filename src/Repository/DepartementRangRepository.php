<?php

namespace App\Repository;

use App\Entity\DepartementRang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepartementRang>
 */
class DepartementRangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepartementRang::class);
    }

    public function findByTypeAndSite($type, $site): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.typeDemande', 't')
            ->join('r.site', 's')
            ->andWhere('t.id = :idType')
            ->andWhere('s.id = :idSite')
            ->setParameter('idType', $type->getId())
            ->setParameter('idSite', $site->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDepartementAndSite($departement, $site): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.departement', 'd')
            ->join('r.site', 's')
            ->andWhere('d.id = :idDep')
            ->andWhere('s.id = :idSite')
            ->setParameter('idDep', $departement->getId())
            ->setParameter('idSite', $site->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDepartement($dep): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.departement', 'd')
            ->andWhere('d.id = :idDep')
            ->setParameter('idDep', $dep->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
