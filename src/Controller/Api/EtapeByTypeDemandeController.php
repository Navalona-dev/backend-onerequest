<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\TypeDemande;
use App\Entity\TypeDemandeEtape;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TypeDemandeEtapeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Api\EtapeByTypeDemandeController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EtapeByTypeDemandeController extends AbstractController
{
    public function __invoke(
        TypeDemande $data, 
        Site $site,
        Request $request,
        EntityManagerInterface $em,
        TypeDemandeEtapeRepository $typeRepo,
        SiteRepository $siteRepo
    ): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Type de demande non trouvé.');
        }

        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $etapes = $typeRepo->findByTypeAndSite($site, $data);

        $etapeTab = [];

        foreach ($etapes as $etape) {

            $etapeTab[] = [
                'id' => $etape->getId(),
                'title' => $etape->getTitle(),
                'titleEn' => $etape->getTitleEn(),
                'createdAt' => $etape->getCreatedAt()?->format('Y-m-d H:i:s'),
                'ordre' => $etape->getOrdre(),
                'statutInitial' => $etape->getStatutInitial(),
                'statutFr' => TypeDemandeEtape::STATUT[$etape->getStatut()],
                'statutEn' => TypeDemandeEtape::STATUT_EN[$etape->getStatut()],
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
                ],
                'niveauHierarchique' => $etape->getNiveauHierarchique() ? [
                    'id' => $etape->getNiveauHierarchique()->getId(),
                    'nom' => $etape->getNiveauHierarchique()->getNom(),
                    'nomEn' => $etape->getNiveauHierarchique()->getNomEn()
                ] : null
            ];
        }

        return new JsonResponse($etapeTab);
    }
}
