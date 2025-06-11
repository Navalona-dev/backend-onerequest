<?php

namespace App\Controller\Api;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CodeCouleurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CodeCouleurBySiteController extends AbstractController
{
    public function __invoke(Site $data, CodeCouleurRepository $codeRepo, EntityManagerInterface $em): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $codes = $codeRepo->findBy(['site' => $data]);

        $codeTab = [];

        foreach ($codes as $code) {
            // Protection si l'utilisateur n'a pas de site
            $siteData = null;
            if ($code->getSite()) {
                $siteData = [
                    'id' => $code->getSite()->getId(),
                    'nom' => $code->getSite()->getNom(),
                ];
            }

            $codeTab[] = [
                'id' => $code->getId(),
                'bgColor' => $code->getBgColor(),
                'textColor' => $code->getTextColor(),
                'btnColor' => $code->getBtnColor(),
                'textColorHover' => $code->getTextColorHover(),
                'btnColorHover' => $code->getBtnColorHover(),
                'colorOne' => $code->getColorOne(),
                'colorTwo' => $code->getColorTwo(),
                'site' => $siteData,
                'isActive' => $code->getIsActive()
            ];
        }

        return new JsonResponse($codeTab);
    }
}
