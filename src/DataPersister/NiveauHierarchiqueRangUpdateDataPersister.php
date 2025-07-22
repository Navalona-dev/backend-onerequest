<?php

namespace App\DataPersister;

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
        private NiveauHierarchiqueRangRepository $rangRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof NiveauHierarchiqueRang;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $departement = $data->getDepartement();
        $niveau = $data->getNiveauHierarchique();

        $rangs = $this->rangRepo->findByDepartement($departement);
        $newRang = $data->getRang();
        $currentId = $data->getId(); // ID de l'entité en cours de modification
        
        foreach ($rangs as $rang) {
            if ($rang->getRang() === $newRang && $rang->getId() !== $currentId) {
                throw new BadRequestHttpException('Ce rang existe déjà pour ce département.');
            }
        }

        $method = strtoupper($operation->getMethod());

        if ($method === 'PATCH') {
            $data->setCreatedAt(new \DateTime());
        } 
        
        $this->entityManager->persist($data);

        $this->entityManager->flush();

        return $data;
    }
}