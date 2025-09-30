<?php
namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PrivilegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class UserRegisterController
{
    public function __invoke(
        Request $request,
        UserPasswordHasherInterface $hasher, 
        PrivilegeRepository $privilegeRepo,
        EntityManagerInterface $em,
        UserRepository $userRepo
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'], $data['nom'], $data['confirmPassword'])) {
            throw new BadRequestHttpException('Champs obligatoires manquants');
        }

        if ($data['password'] !== $data['confirmPassword']) {
            throw new BadRequestHttpException('Les mots de passe ne correspondent pas.');
        }

        // Vérification si l'email existe déjà
        $existingUser = $userRepo->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            throw new BadRequestHttpException('Cet email est déjà utilisé.');
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setPhone($data['phone'] ?? null);
        $user->setAdresse($data['adresse'] ?? null);
        $user->setRoles(["ROLE_USER"]);

        $hashedPassword = $hasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $privilege = $privilegeRepo->findOneBy(['id' => 10]);
        if ($privilege) {
            $user->addPrivilege($privilege);
        }

        $em->persist($user);
        $em->flush();

        // Persistance dans le DataPersister => retourne 201 ici
        return new JsonResponse([
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'phone' => $user->getPhone(),
            'adresse' => $user->getAdresse(),
        ], 201);
    }


}
