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

class RangByNiveauAndDepartementController extends AbstractController
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

        $rang = $rangRepo->findOneByDepartementAndNiveau($dep, $niveau);

        $rangTab = [
            'id' => $rang->getId(),
            'rang' => $rang->getRang(),
        ];

        return new JsonResponse($rangTab);
    }
}
