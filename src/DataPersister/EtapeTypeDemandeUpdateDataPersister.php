<?php

namespace App\DataPersister;

use App\Entity\TypeDemandeEtape;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\TypeDemandeRepository;
use App\Repository\TypeDemandeEtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EtapeTypeDemandeUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack ,
        private TypeDemandeEtapeRepository $typeRepo,
        private SiteRepository $siteRepo,
        private TypeDemandeRepository $typeDemandeRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof TypeDemandeEtape;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        $request = $this->requestStack->getCurrentRequest();

        $payload = json_decode($request->getContent(), true);

        $title = $payload['title'] ?? null;
        $titleEn = $payload['titleEn'] ?? null;
        $ordre = $payload['ordre'] ?? null;$siteUrl = $payload['site'] ?? null; 
        $typeDemandeUrl = $payload['typeDemande'] ?? null; 
        $niveauHierarchiqueUrl = $payload['niveauHierarchique'] ?? null;

        $siteId = $siteUrl ? (int) basename($siteUrl) : null;
        $typeDemandeId = $typeDemandeUrl ? (int) basename($typeDemandeUrl) : null;

        $site = $this->siteRepo->findOneBy(['id' => $siteId]);
        $typeDemande = $this->typeDemandeRepo->findOneBy(['id' => $typeDemandeId]);

        $etapes = $this->typeRepo->findByTypeAndSite($site, $typeDemande);

        $orderTab = [];
        foreach($etapes as $etape) {
            $orderTab[] = $etape->getOrdre();
        }

        $newOrder = $data->getOrdre();

        $existingEtape = $this->typeRepo->findOneBy([
            'site' => $site,
            'typeDemande' => $typeDemande,
            'ordre' => $newOrder
        ]);

        if ($method === 'PATCH') {
            if ($existingEtape && $existingEtape->getId() !== $data->getId() && in_array($newOrder, $orderTab)) {
                throw new BadRequestHttpException("L'ordre $ordre existe déjà pour ce site et ce type de demande.");
            }

            $data->setUpdatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}