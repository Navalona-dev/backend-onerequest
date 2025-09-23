<?php
namespace App\Controller\Api;
use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RangsByNiveauAndDepartementController extends AbstractController
{
    public function __invoke(
        NiveauHierarchique $niveau,
        Departement $dep,
        NiveauHierarchiqueRangRepository $rangRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        if (!$niveau) {
            throw new NotFoundHttpException('Niveau hierarchique non trouvé.');
        }

        $rangs = $rangRepo->findByDepartementAndNiveau($dep, $niveau);

        $rangTypeTab = null;
        $rangTab = [];
        foreach($rangs as $rang) {
            if ($rang->getTypeDemande()) {
                $rangTypeTab = [
                    'id' => $rang->getTypeDemande()->getId(),
                    'nom' => $rang->getTypeDemande()->getNom(),
                    'nomEn' => $rang->getTypeDemande()->getNomEn()
                ];
            }

            $rangTab[] = [
                'id' => $rang->getId(),
                'rang' => $rang->getRang(),
                'typeDemande' => $rangTypeTab
            ];
        }

        return new JsonResponse($rangTab);

    }
}
