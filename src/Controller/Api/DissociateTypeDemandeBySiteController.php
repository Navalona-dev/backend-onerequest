<?php
namespace App\Controller\Api;

use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use App\Repository\SiteRepository;
use App\Repository\DemandeRepository;
use App\Entity\NiveauHierarchiqueRang;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeDemandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DissociateTypeDemandeBySiteController extends AbstractController
{
    public function __invoke(
        Request $request, 
        EntityManagerInterface $em,
        SiteRepository $siteRepo,
        TypeDemandeRepository $typeRepo,
        DemandeRepository $demandeRepo
    ): JsonResponse
    {
        $typeId = $request->attributes->get('idType');
        $siteId = $request->attributes->get('idSite');

        if (!$typeId) {
            throw new NotFoundHttpException('Type de demande non trouvé.');
        }

        if (!$siteId) {
            throw new NotFoundHttpException('Site de demande non trouvé.');
        }

        $site = $siteRepo->findOneBy(['id' => $siteId]);
        $type = $typeRepo->findOneBy(['id' => $typeId]);

        $demandes = $demandeRepo->findBySiteAndTypeDemande($site, $type);

        if(count($demandes) > 0) {
            throw new BadRequestHttpException(
                "Impossible de dissocier ce type de demande : il existe déjà des demandes associées."
            );
        }

        $type->removeSite($site);
        
        $em->flush();

        return new JsonResponse(['message' => 'Type de demande dissocié avec succès.'], 200);
    }
}
