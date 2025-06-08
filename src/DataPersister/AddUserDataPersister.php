<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\CodeCouleur;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Exception\EmailAlreadyExistsException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddUserDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function supports($data, array $context = []): bool
    {
        // Ce persister ne s'applique que sur l'entité User
        return $data instanceof User;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof User && strtoupper($operation->getMethod()) === 'POST') {
           $password = "password12345";
           $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $password
            );
            $data->setPassword($hashedPassword);
            $data->setCreatedAt(new \DateTime());
        }

        //verifier si un mail existe déjà

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $data->getEmail()
        ]);

        if ($existingUser !== null) {
            throw new BadRequestHttpException(json_encode([
                'message' => 'Un compte avec cet email existe déjà.'
            ]));
        }else {
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }

        return $data;
    }
}
