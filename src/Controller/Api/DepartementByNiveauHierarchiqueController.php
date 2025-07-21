<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CodeCouleurRepository;
use App\Repository\DepartementRepository;
use App\Repository\NiveauHierarchiqueRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartementByNiveauHierarchiqueController extends AbstractController
{
    public function __invoke(NiveauHierarchique $data, DepartementRepository $depRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        $departements = $depRepo->findByNh($data);

        $depTab = [];

        foreach ($departements as $dep) {
            // Protection si l'utilisateur n'a pas de site
            $niveauData = [];
            foreach ($dep->getNiveauHierarchiques() as $niveau) {
                $niveauData[] = [
                    'id' => $niveau->getId(),
                    'nom' => $niveau->getNom(),
                    'nomEn' => $niveau->getNomEn()
                ];
            }

            $depTab[] = [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomEn(),
                'description' => $dep->getDescription(),
                'descriptionEn' => $dep->getDescriptionEn(),
                'isActive' => $dep->getIsActive()
            ];
        }

        return new JsonResponse($depTab);
    }
}
