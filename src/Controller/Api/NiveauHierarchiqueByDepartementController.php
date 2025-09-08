<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CodeCouleurRepository;
use App\Repository\NiveauHierarchiqueRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NiveauHierarchiqueByDepartementController extends AbstractController
{
    public function __invoke(Departement $data, NiveauHierarchiqueRepository $niveauRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        $niveaux = $niveauRepo->findByDepartement($data);

        $niveauTab = [];

        foreach ($niveaux as $niveau) {
            // Protection si l'utilisateur n'a pas de site
            $departementData = [];
            foreach ($niveau->getDepartements() as $dep) {
                $departementData[] = [
                    'id' => $dep->getId(),
                    'nom' => $dep->getNom(),
                    'nomEn' => $dep->getNomEn()
                ];
            }

            $privilege = $niveau->getPrivilege();
            $privilegeData= [];

            if($privilege) {
                $privilegeData = [
                    'id' => $privilege->getId(),
                    'title' => $privilege->getTitle()
                ];
            }

            $niveauTab[] = [
                'id' => $niveau->getId(),
                'nom' => $niveau->getNom(),
                'nomEn' => $niveau->getNomEn(),
                'description' => $niveau->getDescription(),
                'descriptionEn' => $niveau->getDescriptionEn(),
                'isActive' => $niveau->getIsActive(),
                'privilege' => $privilegeData
            ];
        }

        return new JsonResponse($niveauTab);
    }
}
