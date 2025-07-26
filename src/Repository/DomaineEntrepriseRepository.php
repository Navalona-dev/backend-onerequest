<?php

namespace App\Repository;

use App\Entity\DomaineEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DomaineEntreprise>
 */
class DomaineEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineEntreprise::class);
    }

    public function findByEntreprise($entreprise): array
    {
        return $this->createQueryBuilder('d')
            ->join('d.entreprises', 'e')
            ->andWhere('e.id = :idEntreprise')
            ->setParameter('idEntreprise', $entreprise->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    
}
