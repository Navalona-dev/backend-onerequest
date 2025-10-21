<?php

namespace App\Controller\Api;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use App\Repository\DomaineEntrepriseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListeDepartementController extends AbstractController
{
    public function __invoke(DepartementRepository $depRepo): JsonResponse
    {

        $departements = $depRepo->findAll();
        $depTab = [];
        foreach($departements as $dep) {
            $depTab[] = [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomEn()
            ];
        }


        return new JsonResponse($depTab);
    }
}
