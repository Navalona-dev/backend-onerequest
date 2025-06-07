<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users', name: 'api_users_')]
class UserController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $formattedUsers = array_map(function(User $user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'roles' => $user->getRoles(),
                'profile' => $user->getProfile(),
                'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }, $users);
        
        return $this->json($formattedUsers);
        
    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifie si l'e-mail existe déjà
        $existingUser = $userRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json([
                'error' => 'Un utilisateur avec cet e-mail existe déjà.'
            ], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom'] ?? null);
        $user->setEmail($data['email']);
        $user->setProfile($data['profile']);
        $user->setRoles($data['roles'] ?? ['ROLE_USER']);
        $user->setCreatedAt(new \DateTime());

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Mise à jour des champs s'ils sont présents dans la requête
        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        if (isset($data['email'])) {
            // Vérifie que l'email n'est pas déjà utilisé par un autre user
            $existingUser = $userRepository->findOneBy(['email' => $data['email']]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                return $this->json(['error' => 'Un utilisateur avec cet e-mail existe déjà.'], Response::HTTP_CONFLICT);
            }
            $user->setEmail($data['email']);
        }

        // Gestion du champ profile (null si vide)
        if (array_key_exists('profile', $data)) {
            $user->setProfile($data['profile'] !== '' ? $data['profile'] : null);
        }

        // Mise à jour des roles si présents
        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        // Mise à jour du mot de passe uniquement s'il est fourni (non vide)
        if (!empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        $user->setUPdatedAt(new \DateTime());

        $entityManager->flush();

        return $this->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ], Response::HTTP_OK);
    }


    #[Route('/{id}', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        $user = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'roles' => $user->getRoles(),
            'profile' => $user->getProfile(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
        return $this->json($user);
    }


    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'Utilisateur supprimé avec succès.'], Response::HTTP_OK);
    }

}

