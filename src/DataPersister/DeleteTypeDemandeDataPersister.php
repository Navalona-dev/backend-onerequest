<?php

namespace App\DataPersister;

use App\Entity\Privilege;
use App\Entity\TypeDemande;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteTypeDemandeDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof TypeDemande;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'DELETE') {
            $demandes = $data->getDemandes();

            if(count($demandes) > 0) {
                throw new BadRequestHttpException(
                    "Impossible de dissocier ce type de demande : il existe déjà des demandes associées."
                );
            }
    
        } 
        
        $this->entityManager->remove($data);

        $this->entityManager->flush();

        return $data;
    }
}