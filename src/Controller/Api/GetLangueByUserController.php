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

class GetLangueByUserController extends AbstractController
{
    public function __invoke(User $data, LangueRepository $langueRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $langue = $data->getLangue();

        if (!$langue) {
            return new JsonResponse([
                'message' => 'Aucune langue n’est associée à cet utilisateur.',
            ], Response::HTTP_NO_CONTENT); // ou HTTP 200 selon ton besoin
        }

        return new JsonResponse([
            'id' => $langue->getId(),
            'titleFr' => $langue->getTitleFr(),
            'titleEn' => $langue->getTitleEn(),
            'isActive' => $langue->getIsActive(),
            'icon' => $langue->getIcon(),
            'indice' => $langue->getIndice(),
            'message' => 'Langue sélectionnée avec succès.',
        ]);
    }

}

