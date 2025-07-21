<?php

namespace App\Repository;

use App\Entity\NiveauHierarchique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NiveauHierarchique>
 */
class NiveauHierarchiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauHierarchique::class);
    }

    public function findByDepartement($departement): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.departements', 'd')
            ->andWhere('d.id = :idDep')
            ->setParameter('idDep', $departement->getId())
            ->getQuery()
            ->getResult()
        ;
    }

   
}
