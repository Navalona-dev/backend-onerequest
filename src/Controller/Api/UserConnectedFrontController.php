<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserConnectedFrontController extends AbstractController
{
    public function __invoke(
        Security $security, 
        UserRepository $userRepo,
    ): JsonResponse
    {
        $user = $security->getUser();
        //$email = $user->getEmail();

        //$user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $privileges = [];

        foreach ($user->getPrivileges() as $privilege) {
            $privileges[] = [
                'id' => $privilege->getId(),
                'title' => $privilege->getTitle(),
                'libelleFr' => $privilege->getLibelleFr(),
                'libelleEn' => $privilege->getLibelleEn()
                // Ajoute d'autres champs si nécessaire
            ];
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'privileges' => $privileges,
        ]);
    }
}

