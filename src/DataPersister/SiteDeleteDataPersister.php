<?php

namespace App\DataPersister;

use App\Entity\Site;
use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SiteDeleteDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Site;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());
        if ($method === 'DELETE') {
            $demandes = $data->getDemandes();
            if (count($demandes) > 0) {
                throw new HttpException(
                    409, 
                    "Impossible de supprimer ce site : il existe déjà des demandes associées, vous pouvez seulement le rendre indisponible."
                );
            } elseif ($data->getIsCurrent() === true) {
                throw new HttpException(
                    403, 
                    "Suppression impossible : ce site est actuellement utilisé. Veuillez sélectionner un autre site avant de pouvoir le supprimer."
                );
            }
            else {
                $this->entityManager->remove($data);
            }
        }

        $this->entityManager->flush();

        return $data;
    }
}
