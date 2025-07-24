<?php

namespace App\DataPersister;

use App\Entity\NiveauHierarchique;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class NiveauHierarchiqueDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof NiveauHierarchique;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === "DELETE") {
            $niveauRangs = $data->getNiveauHierarchiqueRangs();

            foreach($niveauRangs as $rang) {
                $this->entityManager->remove($rang);
            }

            $this->entityManager->remove($data);

            $this->entityManager->flush();

            return null;
        } 

    }
}