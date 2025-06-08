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

final class CodeCouleurGetActiveController extends AbstractController
{
    public function __invoke(int $id, CodeCouleurRepository $repository): JsonResponse
    {
        $codeCouleurs = $repository->findBy([
            'site' => $id,
            'isActive' => true
        ]);

        if(count($codeCouleurs) > 0) {
            $codeCouleur = $codeCouleurs[0];

            if (!$codeCouleur) {
                return new JsonResponse(['message' => 'Aucun code couleur actif trouvé.'], 404);
            }
    
            return new JsonResponse([
                'id' => $codeCouleur->getId(),
                'message' => "Ce site a un code couleur activé",
            ]);
        } else {
            return new JsonResponse([
                'message' => 'Ce site n\'a pas encore de code couleur activé',
            ]);
        }

       
    }
}
