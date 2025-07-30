<?php
namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Departement;
use App\Entity\DepartementRang;
use App\Entity\NiveauHierarchique;
use App\Entity\NiveauHierarchiqueRang;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DissocieDepartementBySiteController extends AbstractController
{
    public function __invoke(
        Request $request, 
        EntityManagerInterface $em,
        Departement $dep,
        Site $site
    ): JsonResponse
    {
        if (!$dep) {
            throw new NotFoundHttpException('Departement non trouvé.');
        }
        if (!$site) {
            throw new NotFoundHttpException('Site non trouvé.');
        }

        $rangs = $dep->getDepartementRangs();
        foreach($rangs as $rang) {
            $em->remove($rang);
        }

        $dep->removeSite($site);
        $em->flush();

        return new JsonResponse(['message' => 'Departement dissocié avec succès.'], 200);
    }
}
