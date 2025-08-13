<?php

namespace App\Controller\Api;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DomaineEntrepriseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomaineByEntrepriseController extends AbstractController
{
    public function __invoke(EntrepriseRepository $entrepriseRepo, DomaineEntrepriseRepository $domaineRepo, EntityManagerInterface $em): JsonResponse
    {
        $entreprise = $entrepriseRepo->findOneBy(['id' => 1]);

        if (!$entreprise) {
            throw new NotFoundHttpException('Entreprise non trouvé.');
        }

        $categories = $entreprise->getCategorieDomaineEntreprises();

        $domaineTab = [];
        foreach ($categories as $categorie) {
            foreach ($categorie->getDomaines() as $domaine) {
                
                $domaineTab[] = [
                    'id' => $domaine->getId(),
                    'libelle' => $domaine->getLibelle(),
                    'libelleEn' => $domaine->getLibelleEn(),
                    'description' => $domaine->getDescription(),
                    'descriptionEn' => $domaine->getDescriptionEn(),
                    'categorieDomaineEntreprise' => [
                        'id' => $categorie->getId(),
                        'nom' => $categorie->getNom(),
                        'nomEn' => $categorie->getNomEn()
                    ]
                ];
            }
        }

        return new JsonResponse($domaineTab);
    }
}
