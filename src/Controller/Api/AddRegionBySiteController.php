<?php
// src/Controller/Api/AddRegionBySiteController.php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Region;
use App\Entity\Commune;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddRegionBySiteController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em, int $id): JsonResponse
    {
        $site = $em->getRepository(Site::class)->find($id);
        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $data = json_decode($request->getContent(), true);
        $regionId = $data['region_id'] ?? null;
        $regionNom = $data['region_nom'] ?? null;

        $communeId = $data['commune_id'] ?? null;
        $communeNom = $data['commune_nom'] ?? null;
        $district = $data['district'] ?? null;

        $region = null;

        if ($regionId) {
            $region = $em->getRepository(Region::class)->find($regionId);
            if (!$region) {
                return new JsonResponse(['message' => 'Région introuvable.'], 404);
            }
        } elseif ($regionNom) {
            $region = new Region();
            $region->setNom($regionNom);
            $region->setCreatedAt(new \DateTime());
            $em->persist($region);
        } else {
            return new JsonResponse(['message' => 'Aucune région fournie.'], 400);
        }

        $commune = null;
        
        if ($communeId) {
            $commune = $em->getRepository(Commune::class)->find($communeId);
            if (!$commune) {
                return new JsonResponse(['message' => 'Commune introuvable.'], 404);
            }
        } elseif ($communeNom) {
            $commune = new Commune();
            $commune->setNom($communeNom);
            $commune->setDistrict($district);
            $commune->setRegion($region);
            $commune->setCreatedAt(new \DateTime());
            $em->persist($commune);
        } else {
            return new JsonResponse(['message' => 'Aucune commune fournie.'], 400);
        }

        $site->setRegion($region);
        $site->setCommune($commune);
        $em->flush();

        return new JsonResponse(['message' => 'Région et commune associés avec succès.'], 200);
    }
}
