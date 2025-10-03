<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserConnectedController extends AbstractController
{
    public function __invoke(
        Security $security, 
        UserRepository $userRepo,
        NiveauHierarchiqueRangRepository $rangNiveauRepo
    ): JsonResponse
    {
        $user = $security->getUser();
        //$email = $user->getEmail();

        //$user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $privileges = [];

        foreach ($user->getPrivileges() as $privilege) {
            $privileges[] = [
                'id' => $privilege->getId(),
                'title' => $privilege->getTitle(),
                'libelleFr' => $privilege->getLibelleFr(),
                'libelleEn' => $privilege->getLibelleEn()
                // Ajoute d'autres champs si nécessaire
            ];
        }

        $site = $user->getSite();
        $siteData = null;

        if ($site) {
            $region = $site->getRegion();
            $regionData = null;
            if($region) {
                $regionData = [
                    'id' => $region->getId(),
                    'nom' => $region->getNom(),
                ];
            }

            $commune = $site->getCommune();
            $communeData = null;
            if($commune) {
                $communeData = [
                    'id' => $commune->getId(),
                    'nom' => $commune->getNom(),
                ];
            }

            $siteData = [
                'id' => $site->getId(),
                'nom' => $site->getNom(),
                'region' => $regionData,
                'commune' => $communeData
            ];
        }

        $dep = $user->getDepartement();
        $niveau = $user->getNiveauHierarchique();
        $rangsNiveau = $rangNiveauRepo->findByDepartementAndNiveau($dep, $niveau);
        $rangsNiveauTab = [];
        if(count($rangsNiveau)) {
            foreach($rangsNiveau as $rang) {
                $rangsNiveauTab[] = [
                    'id' => $rang->getId(),
                    'rang' => $rang->getRang()
                ];
            }
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'privileges' => $privileges,
            'site' => $siteData,
            'isSuperAdmin' => $user->getIsSuperAdmin(),
            'message' => 'Un utilisateur connecté.',
            'departement' => $dep ? [
                'id' => $dep->getId(),
                'nom' => $dep->getNom(),
                'nomEn' => $dep->getNomeEn()
            ] : null,

            'niveauHierarchique' => $niveau ? [
                'id' => $niveau->getId(),
                'nom' => $niveau->getNom(),
                'nomEn' => $niveau->getNomEn(),
            ] : null,
            'rangs' => $rangsNiveauTab
        ]);
    }
}

