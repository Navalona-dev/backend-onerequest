<?php

namespace App\Repository;

use App\Entity\TypeDemandeEtape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDemandeEtape>
 */
class TypeDemandeEtapeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDemandeEtape::class);
    }

    public function countByTypeAndSite($site, $type): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->join('t.site', 's')
            ->join('t.typeDemande', 'td')
            ->andWhere('s.id = :siteId')
            ->andWhere('td.id = :typeId')
            ->setParameter('siteId', $site->getId())
            ->setParameter('typeId', $type->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByTypeAndSite($site, $type)
    {
        return $this->createQueryBuilder('t')
            ->join('t.site', 's')
            ->join('t.typeDemande', 'td')
            ->andWhere('s.id = :siteId')
            ->andWhere('td.id = :typeId')
            ->setParameter('siteId', $site->getId())
            ->setParameter('typeId', $type->getId())
            ->getQuery()
            ->getResult(); 
    }

    

}
