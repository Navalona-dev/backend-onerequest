<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Langue;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Repository\LangueRepository;
use App\Controller\Api\UserController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetLangueActiveController extends AbstractController
{
    public function __invoke(Langue $data, LangueRepository $langueRepo, EntityManagerInterface $em): JsonResponse
    {
        $langues = $langueRepo->findAll();
        if(count($langues) > 0) {
            foreach($langues as $langue) {
                $langue->setIsActive(false);
                $em->persist($langue);
            }
        }

        if (!$data) {
            throw new NotFoundHttpException('Langue non trouvé.');
        }

        $data->setIsActive(true);
        
        $em->persist($data);
        $em->flush();

        return new JsonResponse([
            'id' => $data->getId(),
            'titleFr' => $data->getTitleFr(),
            'titleEn' => $data->getTitleEn(),
            'isActive' => $data->getIsActive(),
            'icon' => $data->getIcon(),
            'message' => 'Langue selectionné avec succès.',
        ]);
    }
}

