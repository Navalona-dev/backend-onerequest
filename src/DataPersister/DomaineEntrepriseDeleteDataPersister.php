<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\DomaineEntreprise;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DomaineEntrepriseDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof DomaineEntreprise;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
    
        if ($method === 'DELETE') {
            $typeDemandes = $data->getTypeDemandes();
    
            foreach ($typeDemandes as $type) {
                if (count($type->getDemandes()) > 0) {
                    throw new BadRequestHttpException(
                        "Impossible de supprimer ce domaine : il existe déjà des demandes associées."
                    );
                }
    
                foreach ($type->getDossierAFournirs() as $dossier) {
                    $type->removeDossierAFournir($dossier);
                }
    
                foreach ($type->getDepartementRangs() as $rang) {
                    $type->removeDepartementRang($rang);
                    $this->entityManager->remove($rang);
                }
    
                foreach ($type->getSites() as $site) {
                    $type->removeSite($site);
                }
    
                $this->entityManager->remove($type);
            }
        }
    
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
    
}