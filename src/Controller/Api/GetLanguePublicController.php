<?php

namespace App\Controller\Api;

use App\Repository\LangueRepository;
use App\Repository\HeroSectionRepository;
use App\Controller\Api\LangueByActiveController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetLanguePublicController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(LangueRepository $langueRepo): JsonResponse
    {

        $langues = $langueRepo->findAll();
        $langueTab = [];

        foreach($langues as $langue) {
            $langueTab[] = [
                'id' => $langue->getId(),
                'titleFr' => $langue->getTitleFr(),
                'titleEn' => $langue->getTitleEn(),
                'icon' => $langue->getIcon(),
                'indice' => $langue->getIndice(),
                
            ];
        }

        return new JsonResponse($langueTab);
    }
}
