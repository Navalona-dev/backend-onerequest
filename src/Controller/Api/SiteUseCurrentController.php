<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\SiteRepository;
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

class SiteUseCurrentController extends AbstractController
{
    public function __invoke(SiteRepository $siteRepo): JsonResponse
    {
        $site = $siteRepo->findOneBy(['isCurrent' => true]);

        if (!$site) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $region = [
            'id' => $site->getRegion()->getId(),
            'nom' => $site->getRegion()->getNom()
        ];

        $commune = [
            'id' => $site->getCommune()->getId(),
            'nom' => $site->getCommune()->getNom(),
        ];

        $departements = $site->getDepartements();
        $departementData = [];
        foreach($departements as $dep) {
            $departementData[] = [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomEn(),
                'description' => $dep->getDescription(),
                'descriptionEn' => $dep->getDescriptionEn()
            ];
        }

        return new JsonResponse([
            'id' => $site->getId(),
            'nom' => $site->getNom(),
            'isCurrent' => $site->getIsCurrent(),
            'region' => $region,
            'commune' => $commune,
            'departements' => $departementData,
            'message' => 'Un site trouvé.',

        ]);
    }
}

