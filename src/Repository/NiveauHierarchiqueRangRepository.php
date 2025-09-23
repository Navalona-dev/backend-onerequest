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

    public function findOneByDepartementAndNiveau($departement, $niveau)
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

    public function findByDepartementAndType($departement, $type): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.departement', 'd')
            ->join('r.typeDemande', 't')
            ->andWhere('d.id = :idDep')
            ->andWhere('t.id = :idType')
            ->setParameter('idDep', $departement->getId())
            ->setParameter('idType', $type->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDepartementAndNiveau($departement, $niveau): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.departement', 'd')
            ->join('r.niveauHierarchique', 'n')
            ->andWhere('d.id = :idDep')
            ->andWhere('n.id = :idNiveau')
            ->setParameter('idDep', $departement->getId())
            ->setParameter('idNiveau', $niveau->getId())
            ->getQuery()
            ->getResult()
        ;
    }
    
}
