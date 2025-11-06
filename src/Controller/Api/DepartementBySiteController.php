<?php

namespace App\Controller\Api;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartementBySiteController extends AbstractController
{
    public function __invoke(Site $data, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $departements = $data->getDepartements();
        $depTab = [];

        foreach ($departements as $dep) {
            $rangs = $dep->getDepartementRangs();
            $rangData = [];

            foreach ($rangs as $rang) {
                $rangData[] = [
                    'id' => $rang->getId(),
                    'rang' => $rang->getRang()
                ];
            }

            $depTab[] = [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomEn(),
                'description' => $dep->getDescription(),
                'descriptionEn' => $dep->getDescriptionEn(),
                'isActive' => $dep->getIsActive(),
                'departementRangs' => $rangData
            ];
        }

        return new JsonResponse($depTab);
    }
}
