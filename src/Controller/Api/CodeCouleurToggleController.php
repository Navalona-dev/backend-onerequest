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

final class CodeCouleurToggleController extends AbstractController
{
    public function __invoke(
        CodeCouleur $data, 
        EntityManagerInterface $em,
        CodeCouleurRepository $codeCouleurRepo
    ): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Code couleur non trouvé.');
        }

            $activeCodeCouleurs = $codeCouleurRepo->findBy(['isActive' => true]);

            // Si le code couleur est actif et qu’il est le seul actif
            if ($data->getIsActive() && count($activeCodeCouleurs) === 1) {
                return new JsonResponse([
                    'id' => $data->getId(),
                    'isActive' => $data->getIsActive(),
                    'message' => 'Impossible de désactiver ce code couleur. Activez d\'abord un autre code couleur.',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Désactiver les autres si on active celui-ci
            foreach ($codeCouleurRepo->findAll() as $codeCouleur) {
                if ($codeCouleur !== $data) {
                    $codeCouleur->setIsActive(false);
                    $em->persist($codeCouleur);
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
