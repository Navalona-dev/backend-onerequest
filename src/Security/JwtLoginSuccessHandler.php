<?php
// src/Security/JwtLoginSuccessHandler.php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User; // si tu veux typer plus précisément

class JwtLoginSuccessHandler
{
    public function __invoke(AuthenticationSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $data = $event->getData();

        // Initialiser le tableau des privilèges
        $dataPrivileges = [];

        foreach($user->getPrivileges() as $privilege) {
            $dataPrivileges[] = [
                'id' => $privilege->getId(),
                'title' => $privilege->getTitle(),
                'libelleFr' => $privilege->getLibelleFr(),
                'libelleEn' => $privilege->getLibelleEn()
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

        $data['data'] = [
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'isSuperAdmin' => $user->getIsSuperAdmin(),
            'phone' => $user->getPhone(),
            'adresse' => $user->getAdresse(),
            'privileges' => $dataPrivileges,
            'site' => $siteData
        ];

        $event->setData($data);
    }
}
