<?php

namespace App\DataPersister;

use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use App\Entity\NiveauHierarchiqueRang;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class NiveauHierarchiqueRangUpdateDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NiveauHierarchiqueRangRepository $rangRepo,
        private UserRepository $userRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof NiveauHierarchiqueRang;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $departement = $data->getDepartement();
        $niveau = $data->getNiveauHierarchique();
        $type = $data->getTypeDemande();

        $rangs = $this->rangRepo->findByDepartementAndType($departement, $type);
        $newRang = $data->getRang();
        $currentId = $data->getId();
        
        foreach ($rangs as $rang) {
            if ($rang->getRang() === $newRang && $rang->getId() !== $currentId) {
                throw new BadRequestHttpException('Ce rang existe déjà pour ce département et ce type de demande.');
            }
        }

        $method = strtoupper($operation->getMethod());

        if ($method === 'PATCH') {
            $users = $this->userRepo->findByNiveauAndDep($niveau, $departement);
            
            foreach ($users as $user) {
                if (!$user->getNiveauHierarchiqueRangs()->contains($data)) {
                    $user->addNiveauHierarchiqueRang($data);
                }
            }
            
            
            $data->setCreatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}