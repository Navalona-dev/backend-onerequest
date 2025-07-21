<?php

namespace App\Repository;

use App\Entity\NiveauHierarchiqueRang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NiveauHierarchiqueRang>
 */
class NiveauHierarchiqueRangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauHierarchiqueRang::class);
    }

    public function findByDepartementAndNiveau($departement, $niveau)
    {
        return $this->createQueryBuilder('r')
            ->join('r.departement', 'd')
            ->join('r.niveauHierarchique', 'n')
            ->andWhere('d.id = :idDep')
            ->andWhere('n.id = :idNiv')
            ->setParameter('idDep', $departement->getId())
            ->setParameter('idNiv', $niveau->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
