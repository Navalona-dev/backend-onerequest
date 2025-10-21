<?php

namespace App\DataPersister;

use App\Entity\DepartementRang;
use ApiPlatform\Metadata\Operation;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\DepartementRangRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DepartementRangUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DepartementRangRepository $rangRepo,
        private DemandeRepository $demandeRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof DepartementRang;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $departement = $data->getDepartement();
        $site = $data->getSite();
        $type = $data->getTypeDemande();

        $demandes = $this->demandeRepo->findBySiteAndDep($site, $departement);

        $rangs = $this->rangRepo->findByTypeAndSite($type, $site);
        $rangTab = [];
        foreach($rangs as $rang) {
            $rangTab[] = $rang->getRang();
        }

        $newRang = $data->getRang();

        $existingRang = $this->rangRepo->findOneBy([
            'site' => $site,
            'typeDemande' => $type,
            'rang' => $newRang
        ]);

        if ($existingRang && $existingRang->getId() !== $data->getId() && in_array($newRang, $rangTab)) {
            throw new BadRequestHttpException('Ce rang existe déjà pour ce site et type de demande.');
        }

        if(count($demandes) > 0) {
            throw new HttpException(409, 'Il y a déjà des demandes existantes.');
        }

        $method = strtoupper($operation->getMethod());

        if ($method === 'PUT' || $method === 'PATCH') {
            $data->setUpdatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return [
            'message' => 'Rang ajouté avec succès',
            'id' => $data->getId()
        ];
    }
}