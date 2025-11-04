<?php

namespace App\Controller\Api;

use App\Entity\Demande;
use App\Entity\Traitement;
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
                'date' => $traitement->getDate()?->format('Y-m-d H:i:s'),
                'site' => [
                    'id' => $traitement->getSite()?->getId(),
                    'nom' => $traitement->getSite()?->getNom(),
                    'commune' => $traitement->getSite()?->getCommune() ? [
                        'id' => $traitement->getSite()->getCommune()->getId(),
                        'nom' => $traitement->getSite()->getCommune()->getNom()
                    ] : null,
                    'region' => $traitement->getSite()?->getRegion() ? [
                        'id' => $traitement->getSite()->getRegion()->getId(),
                        'nom' => $traitement->getSite()->getRegion()->getNom()
                    ] : null,
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
                'typeFr' => Traitement::TYPE_FR[$traitement->getType()],
                'typeEn' => Traitement::TYPE_EN[$traitement->getType()],
                'statutFr' => Traitement::STATUT_FR[$traitement->getStatut()],
                'statutEn' => Traitement::STATUT_EN[$traitement->getStatut()],
                'demande' => [
                    'id' => $traitement->getDemande()->getId(),
                    'type' => [
                        'id' => $traitement->getDemande()->getType()->getId(),
                        'nom' => $traitement->getDemande()->getType()->getNom(),
                        'nomEn' => $traitement->getDemande()->getType()->getNomEn()
                    ]
                ]
               
            ];
        }

        return new JsonResponse($traitementTab);
    }
}
