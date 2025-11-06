<?php

namespace App\Controller\Api;

use App\Entity\TypeDemande;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Api\EtapeByTypeDemandeController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EtapeByTypeDemandeController extends AbstractController
{
    public function __invoke(TypeDemande $data, DepartementRepository $langueRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Type de demande non trouvé.');
        }

        $etapes = $data->getTypeDemandeEtapes();
        $etapeTab = [];

        foreach ($etapes as $etape) {

            $etapeTab[] = [
                'id' => $etape->getId(),
                'title' => $etape->getTitle(),
                'titleEn' => $etape->getTitleEn(),
                'createdAt' => $etape->getCreatedAt()?->format('Y-m-d H:i:s'),
                'ordre' => $etape->getOrdre(),
                'statutInitial' => $etape->getStatutInitial(),
                'site' => [
                    'id' => $etape->getSite()->getId(),
                    'nom' => $etape->getSite()->getNom(),
                    'commune' => [
                        'id' => $etape->getSite()->getCommune()->getId(),
                        'nom' => $etape->getSite()->getCommune()->getNom()
                    ],
                    'region' => [
                        'id' => $etape->getSite()->getRegion()->getId(),
                        'nom' => $etape->getSite()->getRegion()->getNom()
                    ],
                    
                ],
                'typeDemande' => [
                    'id' => $etape->getTypeDemande()->getId(),
                    'nom' => $etape->getTypeDemande()->getNom(),
                    'nomEn' => $etape->getTypeDemande()->getNomEn()
                ]
            ];
        }

        return new JsonResponse($etapeTab);
    }
}
