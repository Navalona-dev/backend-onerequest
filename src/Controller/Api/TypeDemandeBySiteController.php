<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Region;
use App\Repository\SiteRepository;
use App\Repository\CommuneRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeDemandeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TypeDemandeEtapeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TypeDemandeBySiteController extends AbstractController
{
    public function __invoke(
        Request $request,
        TypeDemandeRepository $typeRepo, 
        EntrepriseRepository $entrepriseRepo,
        SiteRepository $siteRepo,
        TypeDemandeEtapeRepository $etapeRepo
    ): JsonResponse
    {
        $siteId = $request->attributes->get('id');
    
        if (!$siteId) {
            throw new NotFoundHttpException('ID du site manquant.');
        }
    
        $site = $siteRepo->find($siteId);
    
        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }
    
        $types = $site->getTypeDemandes();

        $typeTab = [];
        foreach ($types as $type) {
            $countEtape = $etapeRepo->countByTypeAndSite($site, $type);
            $domaineData = [];
            $sitesData = [];
            if($type->getDomaine()) {
                $domaineData = [
                    'id' => $type->getDomaine()->getId(),
                    'libelle' => $type->getDomaine()->getLibelle(),
                    'description' => $type->getDomaine()->getDescription(),
                    'libelleEn' => $type->getDomaine()->getLibelleEn(),
                    'descriptionEn' => $type->getDomaine()->getDescriptionEn()
                ];
            }
            $sites = $type->getSites();
            foreach($sites as $site) {
                $region = $site->getRegion();
                $commune = $site->getCommune();

                $sitesData[] = [
                    'id' => $site->getId(),
                    'nom' => $site->getNom(),
                    'region' => $region ? [
                        'id' => $region->getId(),
                        'nom' => $region->getNom()
                    ] : null,
                    'commune' => $commune ? [
                        'id' => $commune->getId(),
                        'nom' => $commune->getNom()
                    ] : null
                ];
            }
            $typeTab[] = [
                'id' => $type->getId(),
                'nom' => $type->getNom(),
                'description' => $type->getDescription(),
                'isActive' => $type->getIsActive(),
                'nomEn' => $type->getNomEn(),
                'descriptionEn' => $type->getDescriptionEn(),
                'domaine' => $domaineData,
                'sites' => $sitesData,
                'countEtape' => $countEtape
            ];
        }
    
        return new JsonResponse($typeTab);
    }
}
