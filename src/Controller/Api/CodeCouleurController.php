<?php

namespace App\Controller\Api;

use App\Entity\CodeCouleur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CodeCouleurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CodeCouleurController extends AbstractController
{
    public function __invoke(CodeCouleur $data, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Code couleur non trouvé.');
        }

        $site = $data->getSite();
            if ($site) {

                foreach ($site->getCodeCouleurs() as $codeCouleur) {
                    if ($codeCouleur !== $data) {
                        $codeCouleur->setIsActive(false);
                        $em->persist($codeCouleur);
                    }
                }
                
            }

        $data->setIsActive(!$data->getIsActive());
        $em->flush();

        return new JsonResponse([
            'id' => $data->getId(),
            'isActive' => $data->getIsActive(),
            'message' => 'Le code couleur a été mis à jour.',
        ]);
    }
}
