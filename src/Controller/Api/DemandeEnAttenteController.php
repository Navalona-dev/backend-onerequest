<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DemandeEnAttenteController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack,
    ) {}
    
    public function __invoke(
        User $user,
        DemandeRepository $demandeRepo, 
        EntityManagerInterface $em,
        NiveauHierarchiqueRangRepository $rangNiveauRepo
    ): JsonResponse
    {
        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $site = $user->getSite();

        $dep = $user->getDepartement();

        $niveau = $user->getNiveauHierarchique();

        $rangsTab = [];

        $rangs = $rangNiveauRepo->findByDepartementAndNiveau($dep, $niveau);

        foreach ($rangs as $rang) {
            $typeDemande = $rang->getTypeDemande();
            $departement = $rang->getDepartement();
            $niv = $rang->getNiveauHierarchique();

            $users = $departement->getUsers();

            $userTab = [];

            if (count($users) > 0) {
                foreach ($users as $u) {
                    $siteUser = $u->getSite();
                    $userTab[] = [
                        'id' => $u->getId(),
                        'email' => $u->getEmail(),
                        'nom' => $u->getNom(),
                        'prenom' => $u->getPrenom(),
                        'site' => $siteUser ? [
                            'id' => $siteUser->getId(),
                            'nom' => $siteUser->getNom()
                        ] : null
                    ];
                }
            }
    
            $rangsTab[] = [
                'id' => $rang->getId(),
                'rang' => $rang->getRang(),
                'typeDemande' => $typeDemande ? [
                    'id' => $typeDemande->getId(),
                    'nom' => $typeDemande->getNom(),
                    'nomEn' => $typeDemande->getNomEn(),
                ] : null,
                'departement' => $departement ? [
                    'id' => $departement->getId(),
                    'nom' => $departement->getNom(),
                    'nomEn' => $departement->getNomEn(),
                    'users' => $userTab
                ] : null,
                'niveauHierarchique' => $niv ? [
                    'id' => $niv->getId(),
                    'nom' => $niv->getNom(),
                    'nomEn' => $niv->getNomEn()
                ] : null
            ];
        }

        $minimumRangs = [];

        if (!empty($rangs)) {
            $minValue = min(array_map(fn($r) => $r->getRang(), $rangs));

            $minimumRangs = array_filter($rangs, function ($r) use ($minValue) {
                return $r->getRang() === $minValue;
            });
        }

        $demandes = [];
        $demandeTab = [];

        $types = [];
        $departements = [];

        foreach ($minimumRangs as $minimumRang) {
            $type = $minimumRang->getTypeDemande();
            $departement = $minimumRang->getDepartement();

            $types[] = $type;
            $departements[] = $departement;
        }

        $typeTitle = [];

        foreach($types as $index => $type) {
            $typeTitle[] = $type->getNom();
            $dep = $departements[$index];
            $demandesPourType = $demandeRepo->findBySiteAndTypeDemandeAndDep($site, $type, $dep);
            $demandes = array_merge($demandes, $demandesPourType);
        }

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

            $departement = [
                'id' => $demande->getDepartement()->getId(),
                'nom' => $demande->getDepartement()->getNom(),
                'nomEn' => $demande->getDepartement()->getNomEn()
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
                'departement' => $departement,
                'reference' => $demande->getReference()
                
            ];
        }

        return new JsonResponse($demandeTab);
    }
}
