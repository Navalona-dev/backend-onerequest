<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CodeCouleurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DemandeByUserController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(User $data, DemandeRepository $demandeRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $demandes = $demandeRepo->findBy(['demandeur' => $data]);

        $demandeTab = [];

        foreach ($demandes as $demande) {
            $userData = null;
            if ($demande->getDemandeur()) {
                $userData = [
                    'id' => $demande->getDemandeur()->getId(),
                    'nom' => $demande->getDemandeur()->getNom(),
                    'prenom' => $demande->getDemandeur()->getPrenom(),
                    'email' => $demande->getDemandeur()->getEmail(),
                    'phone' => $demande->getDemandeur()->getPhone(),
                    'adresse' => $demande->getDemandeur()->getAdresse()
                ];
            }

            if($demande->getSite()) {
                $regionData = [
                    'id' => $demande->getSite()->getRegion()->getId(),
                    'nom' => $demande->getSite()->getRegion()->getNom()
                ];

                $communeData = [
                    'id' => $demande->getSite()->getCommune()->getId(),
                    'nom' => $demande->getSite()->getCommune()->getNom()
                ];

                $siteData = [
                    'id' => $demande->getSite()->getId(),
                    'nom' => $demande->getSite()->getNom(),
                    'region' => $regionData,
                    'commune' => $communeData
                ];
            }

            if($demande->getType()) {
                $typeData = [
                    'id' => $demande->getType()->getId(),
                    'nom' => $demande->getType()->getNom(),
                ];
            }

            $siteNom = strtolower($this->slugger->slug($demande->getSite()->getNom()));
            $siteId = $demande->getSite()->getId();
            $nomFichier = $demande->getFichier();

            $request = $this->requestStack->getCurrentRequest();
            $baseUrl = $request->getSchemeAndHttpHost(); 
            $relativePath = "/uploads/demande_site_" . $siteId . "/" . $nomFichier;

            $demandeTab[] = [
                'id' => $demande->getId(),
                'demandeur' => $userData,
                'type' => $typeData,
                'site' => $siteData,
                'objet' => $demande->getObjet(),
                'contenu' => $demande->getContenu(),
                'fichier' => $baseUrl . $relativePath,
                'statut' => Demande::STATUT[$demande->getStatut()]
            ];
        }

        return new JsonResponse($demandeTab);
    }
}
