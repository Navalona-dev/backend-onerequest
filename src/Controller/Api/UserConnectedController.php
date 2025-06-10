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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserConnectedController extends AbstractController
{
    public function __invoke($email, UserRepository $userRepo): JsonResponse
    {
        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $privileges = [];

        foreach ($user->getPrivileges() as $privilege) {
            $privileges[] = [
                'id' => $privilege->getId(),
                'title' => $privilege->getTitle(),
                // Ajoute d'autres champs si nécessaire
            ];
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'privileges' => $privileges,
            'site' => [
                'id' => $user->getSite()->getId(),
                'nom' => $user->getSite()->getNom()
            ],
            'message' => 'Un utilisateur connecté.',
        ]);
    }
}

