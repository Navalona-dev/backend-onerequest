<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegionDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Region;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === "DELETE") {
            $sites = $data->getSites();

            foreach($sites as $site) {
                $site->setRegion(null);
                $this->entityManager->persist($site);
            }

            $communes = $region->getCommunes();

            foreach($communes as $commune) {
                $sites = $commune->getSites();
                foreach($sites as $site) {
                    $site->setCommune(null);
                    $this->entityManager->persist($site);
                }
                $this->entityManager->remove($commune);
            }
        } 
        
        $this->entityManager->remove($data);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}