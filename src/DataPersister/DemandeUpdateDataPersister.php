<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\Demande;
use App\Entity\DomaineEntreprise;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DemandeUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SiteRepository $siteRepo,
        private RequestStack $requestStack ,
        private ParameterBagInterface $params
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Demande;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'PATCH') {
            $request = $this->requestStack->getCurrentRequest();

            if (!$request) {
                throw new \RuntimeException("Request introuvable");
            }
        
            $objet = $request->get('objet');
            $contenu = $request->get('contenu');
            $siteIri = $request->get('site');
            $typeIri = $request->get('type');
            $demandeurIri = $request->get('demandeur');
            $statut = $request->get('statut');
            $fichier = $request->files->get('fichier');
        
            // Hydrater l'objet manuellement
            $siteId = $this->extractIdFromIri($siteIri);
            $typeId = $this->extractIdFromIri($typeIri);
            $userId = $this->extractIdFromIri($demandeurIri);
        
            $site = $this->siteRepo->find($siteId);
            $type = $this->entityManager->getReference('App\Entity\TypeDemande', $typeId);
            $user = $this->entityManager->getReference('App\Entity\User', $userId);
        
            $data->setSite($site);
            $data->setType($type);
            $data->setDemandeur($user);
            $data->setStatut($statut);
            $data->setObjet($objet);
            $data->setContenu($contenu);
        
            if ($fichier) {
                $nomFichier = uniqid() . '-' . $fichier->getClientOriginalName();
                $uploadDir = $this->params->get('kernel.project_dir') . "/public/uploads/demande_site_" . $site->getId();
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fichier->move($uploadDir, $nomFichier);
                $data->setFichier($nomFichier);
            }
        
            $data->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        
            return $data;
        }
   
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