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
                $entreprises = $domaine->getEntreprise();
                foreach($entreprises as $entreprise) {
                    $entreprise->setDomaineEntreprise(null);
                    $this->entityManager->persist($entreprise);
                }

                $typeDemande = $domaine->getTypeDemandes();
                foreach($typeDemande as $type) {
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