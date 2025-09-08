<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserBySiteController extends AbstractController
{
    public function __invoke(Site $data, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $users = $userRepo->findBy(['site' => $data]);
        $userTab = [];

        foreach ($users as $user) {
            // Protection si l'utilisateur n'a pas de site
            $siteData = null;
            if ($user->getSite()) {
                $siteData = [
                    'id' => $user->getSite()->getId(),
                    'nom' => $user->getSite()->getNom(),
                ];
            }

            $dep = $user->getDepartement();
            $depData = [];
            if($dep) {
                $depData = [
                    'id' => $dep->getId(),
                    'nom' => $dep->getNom(),
                    'nomEn' => $dep->getNomEn()
                ];
            }

            $niveau = $user->getNiveauHierarchique();
            $niveauData = [];
            if($niveau) {
                $niveauData = [
                    'id' => $niveau->getId(),
                    'nom' => $niveau->getNom(),
                    'nomEn' => $niveau->getNomEn()
                ];
            }

            $privileges = [];
            foreach ($user->getPrivileges() as $privilege) {
                $privileges[] = [
                    'id' => $privilege->getId(),
                    'title' => $privilege->getTitle(),
                    'libelleEn' => $privilege->getLibelleEn(),
                    'libelleFr' => $privilege->getLibelleFr()
                ];
            }

            $userTab[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'site' => $siteData,
                'privileges' => $privileges,
                'departement' => $depData,
                'niveauHierarchique' => $niveauData
            ];
        }

        return new JsonResponse($userTab);
    }
}
