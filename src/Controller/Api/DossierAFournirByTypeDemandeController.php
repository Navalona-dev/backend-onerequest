<?php
namespace App\Controller\Api;

use App\Entity\TypeDemande;
use App\Repository\DemandeRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeDemandeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DossierAFournirByTypeDemandeController extends AbstractController
{
    public function __invoke(TypeDemande $data,  SerializerInterface $serializer,): JsonResponse
    {
        if (!$data) {
            throw new NotFoundHttpException('Type non trouvé.');
        }

       $dossiers = $data->getDossierAFournirs();

       // ✅ Serialize les données pour éviter les boucles infinies
       $json = $serializer->serialize($dossiers, 'json', ['groups' => ['dossier:list']]);

       return new JsonResponse($json, 200, [], true);
    }
}
