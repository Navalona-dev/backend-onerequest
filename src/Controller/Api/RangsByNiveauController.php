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

class RangsByNiveauController extends AbstractController
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

        $niveauTab = [
            'id' => $niveau->getId(),
            'nom' => $niveau->getNom(),
            'nomEn' => $niveau->getNomEn()
        ];

        $rangTypeTab = null;
        $rangTab = [];
        $depTab = null;

        foreach($rangs as $rang) {
            if ($rang->getTypeDemande()) {
                $rangTypeTab = [
                    'id' => $rang->getTypeDemande()->getId(),
                    'nom' => $rang->getTypeDemande()->getNom(),
                    'nomEn' => $rang->getTypeDemande()->getNomEn()
                ];
            }

            if($rang->getDepartement()) {
                $depTab = [
                    'id' => $rang->getDepartement()->getId(),
                    'nom' => $rang->getDepartement()->getNom(),
                    'nomEn' => $rang->getDepartement()->getNomEn()
                ];
            }

            $rangTab[] = [
                'id' => $rang->getId(),
                'rang' => $rang->getRang(),
                'typeDemande' => $rangTypeTab,
                'niveau' => $niveauTab,
                'departement' => $depTab
            ];
        }

        return new JsonResponse($rangTab);

    }
}
