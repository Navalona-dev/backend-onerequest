<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Region;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CategorieDomaineEntreprise;
use App\Repository\DomaineEntrepriseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomaineEntrepriseByCategorieController extends AbstractController
{
    public function __invoke(CategorieDomaineEntreprise $data, DomaineEntrepriseRepository $domaineRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Catégorie non trouvé.');
        }

        $domaines = $domaineRepo->findBy(['categorieDomaineEntreprise' => $data]);
        $domaineTab = [];

        foreach ($domaines as $domaine) {
            $domaineTab[] = [
                'id' => $domaine->getId(),
                'libelle' => $domaine->getLibelle(),
                'description' => $domaine->getDescription(),
                'categorie' => [
                    'id' => $domaine->getCategorieDomaineEntreprise()->getId(),
                    'nom' => $domaine->getCategorieDomaineEntreprise()->getNom()
                ],
            ];
        }

        return new JsonResponse($domaineTab);
    }
}
