<?php

namespace App\DataPersister;

use App\Entity\CodeCouleur;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CodeCouleurDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function supports($data, array $context = []): bool
    {
        // Ce persister ne s'applique que sur l'entité CodeCouleur
        return $data instanceof CodeCouleur;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof CodeCouleur && strtoupper($operation->getMethod()) === 'DELETE') {

            if($data->getIsActive() == true) {
                throw new BadRequestHttpException(
                    "Impossible de supprimer ce code couleur : Veuillez d'abord activer un autre code couleur."
                );
            } else {
                $this->entityManager->remove($data);
            }
        } 


        $this->entityManager->flush();

        return $data;
    }
}
