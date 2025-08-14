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

class TypeDemandeByEntrepriseController extends AbstractController
{
    public function __invoke(
        Request $request,
        TypeDemandeRepository $typeRepo, 
        EntrepriseRepository $entrepriseRepo,
    ): JsonResponse
    {
    
        $entreprise = $entrepriseRepo->findOneBy(['id' => 1]);
        if (!$entreprise) {
            throw new NotFoundHttpException('Entreprise non trouvé.');
        }

        $types = [];
        foreach($entreprise->getCategorieDomaineEntreprises() as $categorie) {
            foreach($categorie->getDomaines() as $domaine) {
                $result = $typeRepo->findByDomaine($domaine);
                $types = array_merge($types, $result);
            }
        }

        $typeTab = [];
        foreach ($types as $type) {
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
                'sites' => $sitesData
            ];
        }
    
        return new JsonResponse($typeTab);
    }
}
