<?php

namespace App\DataPersister;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use App\Repository\PrivilegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private PrivilegeRepository $privilegeRepo,
        private RequestStack $requestStack
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
            $request = $this->requestStack->getCurrentRequest();

            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);
            
            if ($existingUser !== null) {
                throw new BadRequestHttpException(json_encode([
                    'message' => 'Un compte avec cet email existe déjà.'
                ]));
            }

            $password = $data->getPassword();

            if(!$password) {
                $password = "password12345";
            }

            if ($request) {
                $is_demandeur = $request->query->get('is_demandeur');

                if($is_demandeur) {
                    $privilege = $this->privilegeRepo->findOneBy(['id' => 10]);

                    $data->addPrivilege($privilege);
                }
            }

            $hashedPassword = $this->passwordHasher->hashPassword($data, $password);
            $data->setPassword($hashedPassword);
            $data->setCreatedAt(new \DateTime());
        }


        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}
