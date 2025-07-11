<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Langue;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Repository\LangueRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetLangueByUserController extends AbstractController
{
    public function __invoke(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        LangueRepository $langueRepo
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $langueId = $data['langueId'] ?? null;
    
        //$user = $userRepo->find($id);
        $langue = $langueRepo->find($langueId);
    
        if (!$user || !$langue) {
            return new JsonResponse(['message' => 'Utilisateur ou langue introuvable'], 404);
        }

        $user->setLangue($langue);
        $em->persist($user);
        $em->flush();
    
        return new JsonResponse(['message' => 'Langue définie pour l\'utilisateur.']);
    }
}

