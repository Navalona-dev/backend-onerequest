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
    ): JsonResponse
    {
        $departementId = $request->get('dep');
        $niveauId = $request->get('id');

        if (!$departementId || !$niveauId) {
            return new JsonResponse(['message' => 'Paramètres manquants.'], 400);
        }

        $dep = $em->getRepository(Departement::class)->find($departementId);
        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }

        $niveau = $em->getRepository(NiveauHierarchique::class)->find($niveauId);
        if (!$niveau) {
            throw new NotFoundHttpException('Niveau hierarchique non trouvé.');
        }

        $niveauxRangs = $niveau->getNiveauHierarchiqueRangs();

        foreach($niveauxRangs as $rang) {
            $em->remove($rang);
        }

        $dep->removeNiveauHierarchique($niveau);
        $em->flush();

        return new JsonResponse(['message' => 'Niveau hiérarchique dissocié avec succès.'], 200);
    }
}
