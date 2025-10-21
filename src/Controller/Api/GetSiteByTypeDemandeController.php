<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSiteByTypeDemandeController extends AbstractController
{
    public function __invoke(Demande $data): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Demande non trouvée.');
        }

        $typeDemande = $data->getType();

        $sites = $typeDemande->getSites();

        $siteTab = [];

        foreach ($sites as $site) {
            $siteTab[] = [
                'id' => $site->getId(),
                'nom' => $site->getNom(),
                'region' => [
                    'id' => $site->getRegion()->getId(),
                    'nom' => $site->getRegion()->getNom()
                ],
                'commune' => [
                    'id' => $site->getCommune()->getId(),
                    'nom' => $site->getCommune()->getNom()
                ]
            ];
        }

        return new JsonResponse($siteTab);
    }
}
