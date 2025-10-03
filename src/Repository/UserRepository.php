<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function findByNiveauAndDep($niveau, $dep): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.departement', 'd')
            ->join('u.niveauHierarchique', 'n')
            ->andWhere('d.id = :idDep')
            ->andWhere('n.id = :idNiv')
            ->setParameter('idDep', $dep->getId())
            ->setParameter('idNiv', $niveau->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    
}
