<?php

namespace App\Controller\Api;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DepartementBySiteController extends AbstractController
{
    public function __invoke(Site $data, DepartementRepository $langueRepo, EntityManagerInterface $em): JsonResponse
    {

        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $departements = $data->getDepartements();
        $depTab = [];
        foreach($departements as $dep) {
            $depTab[] = [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomEn(),
                'description' => $dep->getDescription(),
                'descriptionEn' => $dep->getDescriptionEn(),
                'isActive' => $dep->getIsActive()
            ];
        }

        return new JsonResponse($depTab);
    }
}

