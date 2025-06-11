<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SiteSetCurrentController extends AbstractController
{
    public function __invoke(Site $data, SiteRepository $siteRepo, EntityManagerInterface $em): JsonResponse
    {
        $sites = $siteRepo->getAll();
        if(count($sites) > 0) {
            foreach($sites as $site) {
                $site->setIsCurrent(false);
                $em->persist($site);
            }
        }

        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $data->setIsCurrent(true);
        
        $em->persist($data);
        $em->flush();

        return new JsonResponse([
            'id' => $site->getId(),
            'nom' => $site->getNom(),
            'isCurrent' => $site->getIsCurrent(),
            'message' => 'Site selectionné avec succès.',
        ]);
    }
}

