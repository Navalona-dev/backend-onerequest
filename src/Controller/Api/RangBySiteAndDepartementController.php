<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRangRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RangBySiteAndDepartementController extends AbstractController
{
    public function __invoke(Site $site, Departement $dep, DepartementRangRepository $rangRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        $rangs = $rangRepo->findByDepartementAndSite($dep, $site);
        $rangTab = [];

        foreach ($rangs as $rang) {
            $typeDemandeData = [];
            if($rang->getTypeDemande()) {
                $type = $rang->getTypeDemande();
                $typeDemandeData = [
                    'id' => $type->getId(),
                    'nom' => $type->getNom(),
                ];
            }
            $rangTab[] = [
                'id' => $rang->getId(),
                'rang' => $rang->getRang(),
                'typeDemande' => $typeDemandeData
            ];
        }

        return new JsonResponse($rangTab);
    }
}
