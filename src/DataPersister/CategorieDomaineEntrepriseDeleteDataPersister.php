<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\DomaineEntreprise;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\CategorieDomaineEntreprise;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CategorieDomaineEntrepriseDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof CategorieDomaineEntreprise;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'DELETE') {
            $domaines = $data->getDomaines();
            foreach($domaines as $domaine) {
                $entreprises = $domaine->getEntreprises();
                dd(count($entreprises));
                foreach($entreprises as $entreprise) {
                    $entreprise->removeDomaineEntreprise($domaine);
                }

                $typeDemande = $domaine->getTypeDemandes();
                foreach($typeDemande as $type) {
                    $demandes = $type->getDemandes();
                    if(count($demandes) < 1) {
                        throw new BadRequestHttpException('Type déjà une demande.');
                    }

                    foreach($demandes as $demande) {
                        $this->entityManager->remove($demande);
                    }
                    $dossiers = $type->getDossierAFournirs();
                    foreach($dossiers as $dossier) {
                        $type->removeDossierAFournir($dossier);
                    }
                    $depRangs = $type->getDepartementRangs();
                    foreach($depRangs as $rang) {
                        $this->entityManager->remove($rang);
                    }

                    $sites = $type->getSites();
                    foreach($sites as $site) {
                        $type->removeSite($site);
                    }
                    
                    $this->entityManager->remove($type);
                }

                $this->entityManager->remove($domaine);
            }
           
        } 
        
        $this->entityManager->remove($data);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}