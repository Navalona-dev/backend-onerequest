<?php

namespace App\Controller\Api;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DomaineEntrepriseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategorieByEntrepriseController extends AbstractController
{
    public function __invoke(EntrepriseRepository $entrepriseRepo): JsonResponse
    {
        $entreprise = $entrepriseRepo->findOneBy(['id' => 1]);

        if (!$entreprise) {
            throw new NotFoundHttpException('Entreprise non trouvé.');
        }

        $categories = $entreprise->getCategorieDomaineEntreprises();

        $categorieTab = [];
        foreach ($categories as $categorie) {
            $categorieTab[] = [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom(),
                'nomEn' => $categorie->getNomEn(),
                'description' => $categorie->getDescription(),
                'descriptionEn' => $categorie->getDescriptionEn()
            ];
        }

        return new JsonResponse($categorieTab);
    }
}
