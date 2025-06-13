<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\CodeCouleur;
use Doctrine\ORM\EntityManagerInterface;

class CodeCouleurAddDataPersister implements ProcessorInterface
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
        if ($data instanceof CodeCouleur && strtoupper($operation->getMethod()) === 'POST') {
            $site = $data->getSite();
            if ($site) {

                foreach ($site->getCodeCouleurs() as $codeCouleur) {
                    if ($codeCouleur !== $data) {
                        $codeCouleur->setIsActive(false);
                        $this->entityManager->persist($codeCouleur);
                    }
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
