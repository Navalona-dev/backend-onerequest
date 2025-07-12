<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DemandeBySiteController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(Site $data, DemandeRepository $demandeRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $demandes = $demandeRepo->findBy(['site' => $data]);


        $demandeTab = [];

        foreach ($demandes as $demande) {
            $nomFichier = $demande->getFichier();

            $request = $this->requestStack->getCurrentRequest();
            $baseUrl = $request->getSchemeAndHttpHost(); 
            $relativePath = "/uploads/demande_site_" . $demande->getSite()->getId() . "/" . $nomFichier;

            // Protection si l'utilisateur n'a pas de site
            $siteData = null;
            if ($demande->getSite()) {
                $siteData = [
                    'id' => $demande->getSite()->getId(),
                    'nom' => $demande->getSite()->getNom(),
                ];
            }

            $type = [
                'id' => $demande->getType()->getId(),
                'nom' => $demande->getType()->getNom()
            ];

            $demandeur = [
                'id' => $demande->getDemandeur()->getId(),
                'nom' => $demande->getDemandeur()->getNom(),
                'prenom' => $demande->getDemandeur()->getPrenom(),
                'email' => $demande->getDemandeur()->getEmail(),
            ];

            $demandeTab[] = [
                'id' => $demande->getId(),
                'objet' => $demande->getObjet(),
                'contenu' => $demande->getContenu(),
                'type' => $type,
                'demandeur' => $demandeur,
                'statut' => Demande::STATUT[$demande->getStatut()],
                'statutEn' => Demande::STATUT_EN[$demande->getStatut()],
                'fichier' => $baseUrl . $relativePath,
                
            ];
        }

        return new JsonResponse($demandeTab);
    }
}
