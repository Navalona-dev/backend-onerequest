<?php

namespace App\DataPersister;

use App\Entity\Site;
use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SiteUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Site;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
        // POST: Création => vérifier s'il existe déjà un utilisateur avec cet email
        if ($method === 'POST') {
            $data->setUpdatedAt(new \DateTime());
        }


        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}
