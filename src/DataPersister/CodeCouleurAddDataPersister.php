<?php

namespace App\DataPersister;

use App\Entity\CodeCouleur;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\CodeCouleurRepository;

class CodeCouleurAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CodeCouleurRepository $codeCouleurRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        // Ce persister ne s'applique que sur l'entité CodeCouleur
        return $data instanceof CodeCouleur;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof CodeCouleur && strtoupper($operation->getMethod()) === 'POST') {

                foreach ($this->codeCouleurRepo->findAll() as $codeCouleur) {
                    if ($codeCouleur !== $data) {
                        $codeCouleur->setIsActive(false);
                        $this->entityManager->persist($codeCouleur);
                    }
                
            }

            $data->setIsActive(true);
            $data->setCreatedAt(new \DateTime());
        } 

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
