<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\Privilege;
use App\Entity\Departement;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DepartementDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Departement;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'DELETE') {
            $niveaux = $data->getNiveauHierarchiqueRangs();
            foreach($niveaux as $niveau) {
                $data->removeNiveauHierarchiqueRang($niveau);
            }

            $niveauRangs = $data->getNiveauHierarchiqueRangs();
            foreach($niveauRangs as $nr) {
                $data->removeNiveauHierarchiqueRang($nr);
            }

            $rangs = $data->getDepartementRangs();
            foreach($rangs as $rang) {
                $data->removeDepartementRang($rang);
            }

            $sites = $data->getSites();
            foreach($sites as $site) {
                $data->removeSite($site);
            }

            $departements = $data->getDepartements();
            foreach($departements as $departement) {
                $data->removeDepartement($departement);
            }
        } 
        
        $this->entityManager->remove($data);

        $this->entityManager->flush();

        return $data;
    }
}