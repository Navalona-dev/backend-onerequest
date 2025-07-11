<?php

namespace App\Controller\Api;

use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceListeController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(ServiceRepository $serviceRepo, EntityManagerInterface $em): JsonResponse
    {

        $services = $serviceRepo->findAll();

        $serviceTab = [];

        foreach ($services as $service) {

            $serviceTab[] = [
                'id' => $service->getId(),
                'titleFr' => $service->getTitleFr(),
                'titleEn' => $service->getTitleEn(),
                'icon' => $service->getIcon(),
                'number' => $service->getNumber(),
                'isActive' => $service->getIsActive()
                
            ];
        }

        return new JsonResponse($serviceTab);
    }
}
