<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SiteToggleDisponibleController extends AbstractController
{
    public function __invoke(Site $data, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $data->setIsIndisponible(!$data->getIsIndisponible());
        $em->flush();

        return new JsonResponse([
            'id' => $data->getId(),
            'isIndisponible' => $data->getIsIndisponible(),
            'message' => 'Le site a été mis à jour.',
        ]);
    }
}
