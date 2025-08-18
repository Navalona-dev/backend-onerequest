<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    public function findBySiteAndTypeDemande($site, $type): array
    {
        return $this->createQueryBuilder('d')
            ->join('d.site', 's')
            ->join('d.type', 't')
            ->andWhere('s.id = :idSite')
            ->andWhere('t.id = :idType')
            ->setParameter('idSite', $site)
            ->setParameter('idType', $type)
            ->getQuery()
            ->getResult()
        ;
    }

   
}
