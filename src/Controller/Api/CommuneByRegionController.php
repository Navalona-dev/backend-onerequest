<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Region;
use App\Repository\CommuneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommuneByRegionController extends AbstractController
{
    public function __invoke(Region $data, CommuneRepository $communeRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Region non trouvé.');
        }

        $communes = $communeRepo->findBy(['region' => $data]);
        $communeTab = [];

        foreach ($communes as $commune) {
            $communeTab[] = [
                'id' => $commune->getId(),
                'nom' => $commune->getNom(),
                'district' => $commune->getDistrict()
            ];
        }

        return new JsonResponse($communeTab);
    }
}
