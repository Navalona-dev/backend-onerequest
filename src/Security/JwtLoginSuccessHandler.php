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
                'title' => $privilege->getTitle()
            ];
        }

        $data['data'] = [
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'privileges' => $dataPrivileges
        ];

        $event->setData($data);
    }
}
