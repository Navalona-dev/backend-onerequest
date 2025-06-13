<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {

        $method = strtoupper($operation->getMethod());
          // PUT: Mise à jour => vérifier s'il existe un autre utilisateur avec ce mail
          if ($method === 'PaTCH') {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

            if ($existingUser !== null && $existingUser->getId() !== $data->getId()) {
                throw new BadRequestHttpException(json_encode([
                    'message' => 'Un compte avec cet email existe déjà.'
                ]));
            }

            $data->setUpdatedAt(new \DateTime());
        }


        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}
