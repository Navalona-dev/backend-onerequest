<?php
namespace App\Controller\Api;

use App\Repository\DemandeRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeDemandeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TypeDemandeByDomaineEntrepriseController extends AbstractController
{
    public function __invoke(
        EntrepriseRepository $entrepriseRepo,
        SerializerInterface $serializer,
        TypeDemandeRepository $typeDemandeRepo
    ): JsonResponse {
        $entreprise = $entrepriseRepo->findOneBy(['id' => 1]);
        if (!$entreprise) {
            throw new NotFoundHttpException('Entreprise non trouvée.');
        }

        $domaines = $entreprise->getDomaineEntreprises();
        $typeDemandeTab = [];

        foreach ($domaines as $domaine) {
            $types = $typeDemandeRepo->findByDomaine($domaine);
            foreach ($types as $typeDemande) {
                $typeDemandeTab[] = $typeDemande;
            }
        }

        // ✅ Serialize les données pour éviter les boucles infinies
        $json = $serializer->serialize($typeDemandeTab, 'json', ['groups' => ['type_demande:list']]);

        return new JsonResponse($json, 200, [], true);
    }
}
