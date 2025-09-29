<?php

namespace App\DataPersister;

use App\Entity\DepartementRang;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\DepartementRangRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DepartementRangAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DepartementRangRepository $rangRepo
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

        $rangs = $this->rangRepo->findByTypeAndSite($type, $site);
        $rangTab = [];
        foreach($rangs as $rang) {
            $rangTab[] = $rang->getRang();
        }

        $newRang = $data->getRang();

        //verifier si le rang n'existe pas encore sur le departement
        if (in_array($newRang, $rangTab)) {
            throw new BadRequestHttpException('Ce rang existe déjà pour ce site et type de demande.');
        }

        //dd('ici');

        $method = strtoupper($operation->getMethod());

        if ($method === 'POST') {
            $data->setCreatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return [
            'message' => 'Rang ajouté avec succès',
            'id' => $data->getId()
        ];
    }
}