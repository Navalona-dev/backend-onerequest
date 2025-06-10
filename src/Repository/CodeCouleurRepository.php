<?php

namespace App\Repository;

use App\Entity\CodeCouleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodeCouleur>
 */
class CodeCouleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeCouleur::class);
    }

  
    public function getIsActiveGlobal()
    {
        return $this->createQueryBuilder('c')
        ->where('c.isActive = :active')
        ->andWhere('c.isGlobal = true OR c.isDefault = true')
        ->setParameter('active', true)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }

   
}
