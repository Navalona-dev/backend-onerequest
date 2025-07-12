<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListeStatutDemandeController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        $statutTab = [
            'statut' => Demande::STATUT,
            'statutEn' => Demande::STATUT_EN
        ];

        return new JsonResponse($statutTab);
    }
}
