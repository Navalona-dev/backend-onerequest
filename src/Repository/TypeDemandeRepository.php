<?php

namespace App\Repository;

use App\Entity\TypeDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDemande>
 */
class TypeDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDemande::class);
    }

   
    public function findByDomaine($domaine): array
    {
        return $this->createQueryBuilder('t')
            ->join('t.domaine', 'd')
            ->andWhere('d.id = :val')
            ->andWhere('t.isActive = :active')
            ->setParameter('val', $domaine->getId())
            ->setParameter('active', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySiteAndDomaine($site, $domaine): array
    {
        return $this->createQueryBuilder('t')
            ->join('t.sites', 's')
            ->join('t.domaine', 'd')
            ->andWhere('s.id = :val')
            ->andWhere('d.id = :idDomaine')
            ->andWhere('t.isActive = :active')
            ->setParameter('val', $site->getId())
            ->setParameter('idDomaine', $domaine->getId())
            ->setParameter('active', true)
            ->getQuery()
            ->getResult()
        ;
    }

}
