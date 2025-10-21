<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Demande;
use App\Entity\Traitement;
use App\Repository\SiteRepository;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use App\Repository\DepartementRangRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SendDemandeSiteController extends AbstractController
{
    public function __construct(
        private DemandeRepository $demandeRepo,
        private DepartementRepository $departementRepo,
        private SiteRepository $siteRepo,
        private EntityManagerInterface $em,
        private DepartementRangRepository $depRang
    ) {}

    #[Route('/api/demandes/{id}/send-site', name: 'api_send_demande_site', methods: ['PATCH'])]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $demande = $this->demandeRepo->find($id);
        if (!$demande) {
            return new JsonResponse(['error' => 'Demande introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // ✅ Lire les données JSON
        $payload = json_decode($request->getContent(), true);
        $siteIri = $payload['site'] ?? null;

        if (!$siteIri) {
            throw new BadRequestHttpException('Le site est obligatoire.');
        }

        $siteId = $siteIri ? $this->extractIdFromIri($siteIri) : null;

        $site = $siteId ? $this->siteRepo->find($siteId) : null;

        if ($site) {
            $demande->setSite($site);
        }

        //traiter le departement qui a le rang minimum par rapport au type de demande

        $typeDemande = $demande->getType();

        $rangs = $this->depRang->findByTypeAndSite($typeDemande, $site);

        //recuperer le rang minimum, 

        $rangTab = [];
        $rangTypeTab = [];

        foreach($rangs as $rang) {
            $rangTab[] = $rang->getRang();
            $rangTypeTab[] = $rang->getTypeDemande()->getNom();
        }

        // Récupérer tous les rangs pour ce type de demande et ce site
        $rangs = $this->depRang->findByTypeAndSite($typeDemande, $site);

        if(count($rangs) > 0) {
            $minRang = null;

            if (!empty($rangs)) {
                // Trouver l'objet avec le rang minimum
                $minRang = array_reduce($rangs, function ($carry, $item) {
                    if ($carry === null || $item->getRang() < $carry->getRang()) {
                        return $item;
                    }
                    return $carry;
                });
            }
    
            $dep = $minRang->getDepartement();

            $demande->setDepartement($dep);

            $user = null;
            $userIri = $payload['user'] ?? null;

            if ($userIri) {
                $userId = (int) basename($userIri);
                $user = $this->em->getRepository(User::class)->find($userId);
            }

            $commentaire = $payload['commentaire'] ?? null;

            //gerer le traitement
            $traitement = new Traitement();
            $traitement->setUser($user);
            $traitement->setDemande($demande);
            $traitement->setDate(new \DateTime());
            $traitement->setCommentaire($commentaire);

            $demande->setUpdatedAt(new \DateTime());
            $this->em->persist($traitement);
            $this->em->flush();
        } 
        else {
            //erreur
            throw new BadRequestHttpException(
                "Impossible de transferer cette demande : il n'existe pas encore des rangs de departement lié à ce site."
            );
        }

        return new JsonResponse([
            'message' => 'Demande envoyée au site avec succès.',
            'id' => $demande->getId(),
            'site' => $site ? $site->getId() : null,
        ], Response::HTTP_OK);
    }

    private function extractIdFromIri(?string $iri): ?int
    {
        if (!$iri) return null;
        if (preg_match('#/(\d+)$#', $iri, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
