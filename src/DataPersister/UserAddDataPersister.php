<?php

namespace App\DataPersister;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;

class UserAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
        // POST: Création => vérifier s'il existe déjà un utilisateur avec cet email
        if ($method === 'POST') {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);
            
            if ($existingUser !== null) {
                throw new BadRequestHttpException(json_encode([
                    'message' => 'Un compte avec cet email existe déjà.'
                ]));
            }

            $hashedPassword = $this->passwordHasher->hashPassword($data, 'password12345');
            $data->setPassword($hashedPassword);
            $data->setCreatedAt(new \DateTime());
        }


        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}
