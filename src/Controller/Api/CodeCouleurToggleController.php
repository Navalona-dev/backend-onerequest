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

        if($data->getIsGlobal() != true && $data->getIsDefault() != true ) {
            $site = $data->getSite();

            if ($site) {
                $activeCodeCouleurs = array_filter(
                    $site->getCodeCouleurs()->toArray(),
                    fn(CodeCouleur $cc) => $cc->getIsActive()
                );
    
                // Si le code couleur est actif et qu’il est le seul actif
                if ($data->getIsActive() && count($activeCodeCouleurs) === 1) {
                    return new JsonResponse([
                        'id' => $data->getId(),
                        'isActive' => $data->getIsActive(),
                        'message' => 'Impossible de désactiver ce code couleur. Activez d\'abord un autre code couleur pour ce site.',
                    ], Response::HTTP_BAD_REQUEST);
                }
    
                // Désactiver les autres si on active celui-ci
                foreach ($site->getCodeCouleurs() as $codeCouleur) {
                    if ($codeCouleur !== $data) {
                        $codeCouleur->setIsActive(false);
                        $em->persist($codeCouleur);
                    }
                }
            }
        } else {
            

            $codeCouleurGlobal = $codeCouleurRepo->findOneBy([
                'isGlobal' => true
            ]);
            $codeCouleurDefault = $codeCouleurRepo->findOneBy([
                'isDefault' => true
            ]);

            // Si on active un global, désactiver le default s’il existe
            if ($data->getIsGlobal() && $codeCouleurDefault && $codeCouleurDefault !== $data) {
                $codeCouleurDefault->setIsActive(false);
                $em->persist($codeCouleurDefault);
            }

            // Si on active un default, désactiver le global s’il existe
            if ($data->getIsDefault() && $codeCouleurGlobal && $codeCouleurGlobal !== $data) {
                $codeCouleurGlobal->setIsActive(false);
                $em->persist($codeCouleurGlobal);
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
