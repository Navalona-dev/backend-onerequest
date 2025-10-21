<?php
namespace App\Controller\Api;
use App\Entity\Site;
use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRangRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RangsDepartementBySiteAndDepartementController extends AbstractController
{
    public function __invoke(
        Departement $dep,
        Site $site,
        DepartementRangRepository $depRepo,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        $rangs = $depRepo->findByDepartementAndSite($dep, $site);

        $depTab = [
            'id' => $dep->getId(),
            'nom' => $dep->getNom(),
            'nomEn' => $dep->getNomEn()
        ];

        $rangTypeTab = null;
        $rangTab = [];
        $siteTab = null;

        foreach($rangs as $rang) {
            if ($rang->getTypeDemande()) {
                $rangTypeTab = [
                    'id' => $rang->getTypeDemande()->getId(),
                    'nom' => $rang->getTypeDemande()->getNom(),
                    'nomEn' => $rang->getTypeDemande()->getNomEn()
                ];
            }

            if($rang->getSite()) {
                $communeTab = null;
                $regionTab = null;

                if($rang->getSite()->getCommune()) {
                    $communeTab = [
                        'id' => $rang->getSite()->getCommune()->getId(),
                        'nom' => $rang->getSite()->getCommune()->getNom()
                    ];
                }

                if($rang->getSite()->getRegion()) {
                    $regionTab = [
                        'id' => $rang->getSite()->getRegion()->getId(),
                        'nom' => $rang->getSite()->getRegion()->getNom()
                    ];
                }

                $siteTab = [
                    'id' => $rang->getSite()->getId(),
                    'nom' => $rang->getSite()->getNom(),
                    'commune' => $communeTab,
                    'region' => $regionTab
                ];
            }

            $rangTab[] = [
                'id' => $rang->getId(),
                'rang' => $rang->getRang(),
                'typeDemande' => $rangTypeTab,
                'departement' => $depTab,
                'site' => $siteTab
            ];

           
        }

        return new JsonResponse($rangTab);

    }
}
