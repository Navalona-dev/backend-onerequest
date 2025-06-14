<?php

namespace App\DataPersister;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Region;
use App\Entity\Commune;
use App\Entity\Entreprise;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SiteAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Site;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
    
        if ($method === 'POST') {
            $data->setCreatedAt(new \DateTime());
    
            $site = $data;
    
            $request = $this->requestStack->getCurrentRequest();
            $dataApi = json_decode($request->getContent(), true);
    
            $regionId = $dataApi['region_id'] ?? null;
            $regionNom = $dataApi['region_nom'] ?? null;
    
            $communeId = $dataApi['commune_id'] ?? null;
            $communeNom = $dataApi['commune_nom'] ?? null;
            $district = $dataApi['district'] ?? null;

            $region = null;

            // Gestion région
            if ($regionId) {
                $region = $this->entityManager->getRepository(Region::class)->find($regionId);
                if (!$region) {
                    return new JsonResponse(['message' => 'Région introuvable.'], 404);
                }
            } elseif ($regionNom) {
                $region = new Region();
                $region->setNom($regionNom);
                $region->setCreatedAt(new \DateTime());
                $this->entityManager->persist($region);
            } else {
                return new JsonResponse(['message' => 'Aucune région fournie.'], 400);
            }
    
            // Gestion commune
            if ($communeId) {
                $commune = $this->entityManager->getRepository(Commune::class)->find($communeId);
                if (!$commune) {
                    return new JsonResponse(['message' => 'Commune introuvable.'], 404);
                }
            } elseif ($communeNom) {
                $commune = new Commune();
                $commune->setNom($communeNom);
                $commune->setRegion($region);
                $commune->setDistrict($district);
                $commune->setCreatedAt(new \DateTime());
                $this->entityManager->persist($commune);
            } else {
                return new JsonResponse(['message' => 'Aucune commune fournie.'], 400);
            }
    
            // Hydrate manuellement les autres champs si non désérialisés
            if (empty($site->getNom()) && isset($dataApi['nom'])) {
                $site->setNom($dataApi['nom']);
            }

            if (empty($site->getDescription()) && isset($dataApi['description'])) {
                $site->setDescription($dataApi['description']);
            }
    
            if (empty($site->getEntreprise()) && isset($dataApi['entreprise'])) {
                $entreprise = $this->entityManager->getRepository(Entreprise::class)->find(
                    (int) filter_var($dataApi['entreprise'], FILTER_SANITIZE_NUMBER_INT)
                );
                if ($entreprise) {
                    $site->setEntreprise($entreprise);
                }
            }
    
            $site->setRegion($region);
            $site->setCommune($commune);
        }
    
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    
        return $data;
    }
    
}
