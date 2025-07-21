<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\Privilege;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PrivilegeUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Privilege;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'PATCH') {
            $data->setUpdatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}