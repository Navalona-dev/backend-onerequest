<?php
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

class AddCommuneBySiteController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em, int $id): JsonResponse
    {
        $site = $em->getRepository(Site::class)->find($id);
        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $data = json_decode($request->getContent(), true);

        $communeId = $data['commune_id'] ?? null;
        $communeNom = $data['commune_nom'] ?? null;
        $district = $data['district'] ?? null;
        $regionId = $data['region_id'] ?? null;

        $region = null;

        if($regionId) {
            $region = $em->getRepository(Region::class)->find($regionId);
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

        $site->setCommune($commune);
        $em->flush();

        return new JsonResponse(['message' => 'Commune associés avec succès.'], 200);
    }
}
