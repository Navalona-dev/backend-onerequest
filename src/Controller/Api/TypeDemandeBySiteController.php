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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TypeDemandeBySiteController extends AbstractController
{
    public function __invoke(
        Request $request,
        TypeDemandeRepository $typeRepo, 
        EntrepriseRepository $entrepriseRepo,
        SiteRepository $siteRepo
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
    
        $entreprise = $entrepriseRepo->findOneBy(['id' => 1]);
    
        $types = [];
        foreach ($entreprise->getDomaineEntreprises() as $domaine) {
            $result = $typeRepo->findBySiteAndDomaine($site, $domaine);
            $types = array_merge($types, $result);
        }

        $typeTab = [];
        foreach ($types as $type) {
            $domaineData = [];
            if($type->getDomaine()) {
                $domaineData = [
                    'id' => $type->getDomaine()->getId(),
                    'libelle' => $type->getDomaine()->getLibelle(),
                    'description' => $type->getDomaine()->getDescription(),
                    'libelleEn' => $type->getDomaine()->getLibelleEn(),
                    'descriptionEn' => $type->getDomaine()->getDescriptionEn()
                ];
            }
            $typeTab[] = [
                'id' => $type->getId(),
                'nom' => $type->getNom(),
                'description' => $type->getDescription(),
                'isActive' => $type->getIsActive(),
                'nomEn' => $type->getNomEn(),
                'descriptionEn' => $type->getDescriptionEn(),
                'domaine' => $domaineData
            ];
        }
    
        return new JsonResponse($typeTab);
    }
}
