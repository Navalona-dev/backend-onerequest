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
use App\Controller\Api\GetNiveauHierarchiqueActiveController;

class GetNiveauHierarchiqueActiveController extends AbstractController
{
    public function __invoke(NiveauHierarchiqueRepository $niveauRepo): JsonResponse
    {
        $niveaux = $niveauRepo->findBy(['isActive' => true]);

        $niveauTab = [];

        foreach ($niveaux as $niv) {

            $niveauHierarchiqueRangs = $niv->getNiveauHierarchiqueRangs();
            $rangTab = [];
            foreach($niveauHierarchiqueRangs as $rang) {
                $rangTab[] = [
                    'id' => $rang->getId(),
                    'rang' => $rang->getRang()
                ];
            }

            $deps = $niv->getDepartements();
            $depTab = [];
            foreach($deps as $dep) {
                $depTab[] = [
                    'id' => $dep->getId(),
                    'nom' => $dep->getNom(),
                    'nomEn' => $dep->getNomEn()
                ];
            }

            $users = $niv->getUser();
            $userTab = [];
            foreach($users as $user) {
                $userTab = [
                    'id' => $user->getId(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'email' => $user->getEmail()
                ];
            }

            $niveauTab[] = [
                'id' => $niv->getId(),
                'nom' => $niv->getNom(),
                'nomEn' => $niv->getNomEn(),
                'description' => $niv->getDescription(),
                'descriptionEn' => $niv->getDescriptionEn(),
                'user' => $userTab,
                'niveauHierarchiqueRangs' => $rangTab,
                'departements' => $depTab,
                'privilege' => $niv->getPrivilege() ? [
                    'id' => $niv->getPrivilege()->getId(),
                    'title' => $niv->getPrivilege()->getTitle(),
                    'libelleFr' => $niv->getPrivilege()->getLibelleFr(),
                    'libelleEn' => $niv->getPrivilege()->getLibelleEn()
                ] : null
                
            ];
        }

        return new JsonResponse($niveauTab);
    }
}
