<?php

namespace App\Controller\Api;

use App\Repository\SiteRepository;
use App\Repository\LangueRepository;
use App\Repository\HeroSectionRepository;
use App\Controller\Api\LangueByActiveController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSiteActiveAndDisponibleController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(SiteRepository $siteRepo): JsonResponse
    {

        $sites = $siteRepo->findActiveAndAvailable();

        $siteTab = [];

        foreach($sites as $site) {
            $region = $site->getRegion();
            $regionTab = [];
            if($region) {
                $regionTab = [
                    'id' => $region->getId(),
                    'nom' => $region->getNom()
                ];
            }

            $commune = $site->getCommune();
            $communeTab = [];
            if($commune) {
                $communeTab = [
                    'id' => $commune->getId(),
                    'nom' => $commune->getNom()
                ];
            }

            $siteTab[] = [
                'id' => $site->getId(),
                'nom' => $site->getNom(),
                'isActive' => $site->getIsActive(),
                'commune' => $communeTab,
                'region' => $regionTab
            ];
        }

        return new JsonResponse($siteTab);
    }
}
