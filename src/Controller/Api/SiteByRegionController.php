<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Region;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteByRegionController extends AbstractController
{
    public function __invoke(Region $data, SiteRepository $siteRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $sites = $siteRepo->findBy(['region' => $data]);
        $siteTab = [];

        foreach ($sites as $site) {
            $siteTab[] = [
                'id' => $site->getId(),
                'nom' => $site->getNom(),
                'region' => [
                    'id' => $site->getRegion()->getId(),
                    'nom' => $site->getRegion()->getNom()
                ],
            ];
        }

        return new JsonResponse($siteTab);
    }
}
