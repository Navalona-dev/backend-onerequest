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

class EtapeTypeDemandeDeleteDataPersister implements ProcessorInterface
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

        if ($method === 'DELETE') {
            $demandes = $data->getDemandes();
            if(count($demandes) > 0) {
                throw new BadRequestHttpException("Impossible de supprimer ce type de demande : il existe déjà des demandes associées.");
            }
        } 

        $this->entityManager->remove($data);
        
        $this->entityManager->flush();

        return $data;
    }
}