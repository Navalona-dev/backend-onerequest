<?php

namespace App\Controller\Api;

use App\Entity\Demande;
use App\Repository\SiteRepository;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SendDemandeDepartementController extends AbstractController
{
    public function __construct(
        private DemandeRepository $demandeRepo,
        private DepartementRepository $departementRepo,
        private SiteRepository $siteRepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('/api/demandes/{id}/send-departement', name: 'api_send_demande_departement', methods: ['PATCH'])]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $demande = $this->demandeRepo->find($id);
        if (!$demande) {
            return new JsonResponse(['error' => 'Demande introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // ✅ Lire les données JSON
        $payload = json_decode($request->getContent(), true);
        $departementIri = $payload['departement'] ?? null;
        $siteIri = $payload['site'] ?? null;

        if (!$departementIri) {
            throw new BadRequestHttpException('Le département est obligatoire.');
        }

        $departementId = $this->extractIdFromIri($departementIri);
        $siteId = $siteIri ? $this->extractIdFromIri($siteIri) : null;

        $departement = $this->departementRepo->find($departementId);
        if (!$departement) {
            throw new BadRequestHttpException('Département introuvable.');
        }

        $site = $siteId ? $this->siteRepo->find($siteId) : null;

        $demande->setDepartement($departement);
        if ($site) {
            $demande->setSite($site);
        }

        $demande->setUpdatedAt(new \DateTime());
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Demande envoyée au département avec succès.',
            'id' => $demande->getId(),
            'departement' => $departement->getId(),
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
