<?php

namespace App\DataPersister;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Commune;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CommuneAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Commune;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
        if ($method === 'POST') {
            $data->setCreatedAt(new \DateTime());
        }


        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}
