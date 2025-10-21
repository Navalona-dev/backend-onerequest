<?php
namespace App\Controller\Api;
use App\Entity\User;
use App\Entity\Departement;
use App\Entity\NiveauHierarchique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NiveauHierarchiqueRangRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAllRangByUserController extends AbstractController
{
    public function __invoke(
        User $user,
        NiveauHierarchiqueRangRepository $rangRepo
    ): JsonResponse {
        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $dep = $user->getDepartement();
        $rangsTab = [];
        $minimumRangs = [];
        
        if($dep) {
            $niveaux = $dep->getNiveauHierarchiques();
            foreach ($niveaux as $niveau) {
                $rangs = $rangRepo->findByDepartementAndNiveau($dep, $niveau);
    
                foreach ($rangs as $rang) {
                    $typeDemande = $rang->getTypeDemande();
                    $departement = $rang->getDepartement();
                    $niv = $rang->getNiveauHierarchique();
    
                    $users = $departement->getUsers();
    
                    $userTab = [];
    
                    if (count($users) > 0) {
                        foreach ($users as $u) {
                            $site = $u->getSite();
                            $userTab[] = [
                                'id' => $u->getId(),
                                'email' => $u->getEmail(),
                                'nom' => $u->getNom(),
                                'prenom' => $u->getPrenom(),
                                'site' => $site ? [
                                    'id' => $site->getId(),
                                    'nom' => $site->getNom()
                                ] : null
                            ];
                        }
                    }
            
                    $rangsTab[] = [
                        'id' => $rang->getId(),
                        'rang' => $rang->getRang(),
                        'typeDemande' => $typeDemande ? [
                            'id' => $typeDemande->getId(),
                            'nom' => $typeDemande->getNom(),
                            'nomEn' => $typeDemande->getNomEn(),
                        ] : null,
                        'departement' => $departement ? [
                            'id' => $departement->getId(),
                            'nom' => $departement->getNom(),
                            'nomEn' => $departement->getNomEn(),
                            'users' => $userTab
                        ] : null,
                        'niveauHierarchique' => $niv ? [
                            'id' => $niv->getId(),
                            'nom' => $niv->getNom(),
                            'nomEn' => $niv->getNomEn()
                        ] : null
                    ];
                }
            }
        
            if (!empty($rangsTab)) {
                $minValue = min(array_column($rangsTab, 'rang'));
    
                // récupérer tous les rangs avec ce min
                $minimumRangs = array_filter($rangsTab, function ($r) use ($minValue) {
                    return $r['rang'] === $minValue;
                });
            }
        }
        

        return new JsonResponse([
            'rangs' => $rangsTab,
            'minimumRangs' => array_values($minimumRangs)
        ]);



    }
}
