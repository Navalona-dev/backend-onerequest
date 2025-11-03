<?php

namespace App\Controller\Api;

use App\Entity\Demande;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListeTraitementByDemandeController extends AbstractController
{
    public function __invoke(Demande $data): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Demande non trouvée.');
        }


        $traitements = $data->getTraitements();

        $traitementTab = [];

        foreach ($traitements as $traitement) {
            $traitementTab[] = [
                'id' => $traitement->getId(),
                'createdAt' => $traitement->getCreatedAt(),
                'site' => [
                    'id' => $traitement->getSite()->getId(),
                    'nom' => $traitement->getSite()->getNom()
                ],
                'departement' => [
                    'id' => $traitement->getDepartement()->getId(),
                    'nom' => $traitement->getDepartement()->getNom(),
                    'nomEn' => $traitement->getDepartement()->getNomEn()
                ],
                'user' => [
                    'id' => $traitement->getUser()->getId(),
                    'nom' => $traitement->getUser()->getNom(),
                    'prenom' => $traitement->getUser()->getPrenom(),
                    'email' => $traitement->getUser()->getEmail()
                ],
                'commentaire' => $traitement->getCommentaire(),
                'type' => Traitement::TYPE[$traitement->getType()],
                'statut' => Traitement::STATUT[$traitement->getStatut()]
               
            ];
        }

        return new JsonResponse($traitementTab);
    }
}
