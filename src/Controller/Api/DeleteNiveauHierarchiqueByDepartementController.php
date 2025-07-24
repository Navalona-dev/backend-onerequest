<?php
namespace App\Controller\Api;

use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use App\Entity\NiveauHierarchiqueRang;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteNiveauHierarchiqueByDepartementController extends AbstractController
{
    public function __invoke(
        Request $request, 
        EntityManagerInterface $em,
        NiveauHierarchique $niveau,
        NiveauHierarchiqueRang $niveauRang,
        Departement $dep,
    ): JsonResponse
    {
        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }
        if (!$niveau) {
            throw new NotFoundHttpException('Niveau hierarchique non trouvé.');
        }

        $dep->removeNiveauHierarchique($niveau);
        $dep->removeNiveauHierarchiqueRang($niveauRang);
        $em->flush();

        return new JsonResponse(['message' => 'Niveau hiérarchique dissocié avec succès.'], 200);
    }
}
