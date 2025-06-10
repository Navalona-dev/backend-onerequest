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

final class CodeCouleurGetGlobalActiveController extends AbstractController
{
    public function __invoke(CodeCouleurRepository $codeCouleurRepo): JsonResponse
    {
        $codeCouleur = $codeCouleurRepo->getIsActiveGlobal();
        if (!$codeCouleur) {
            return new JsonResponse(['message' => 'Aucun code couleur actif trouvé.'], 404);
        }
        return $this->json($codeCouleur, 200, [], ['groups' => 'code_couleur:item']);
       
    }
}
