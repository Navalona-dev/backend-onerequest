<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\Demande;
use App\Entity\DomaineEntreprise;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\Operation;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\DepartementRepository;
use App\Repository\DepartementRangRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DemandeAddDataPersister implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SiteRepository $siteRepo,
        private RequestStack $requestStack ,
        private ParameterBagInterface $params,
        private DemandeRepository $demandeRepo,
        private DepartementRepository $depRepo,
        private DepartementRangRepository $depRangRepo
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Demande;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $method = strtoupper($operation->getMethod());

        if ($method === 'POST') {
            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                throw new \RuntimeException("Request introuvable");
            }
        
            // Hydratation manuelle si $data est null
            if (!$data instanceof Demande) {
                $data = new Demande();
            }
        
            $objet = $request->get('objet');
            $contenu = $request->get('contenu');
            $siteIri = $request->get('site');
            $typeIri = $request->get('type');
            $demandeurIri = $request->get('demandeur');
            $statut = $request->get('statut');
            $fichier = $request->files->get('fichier');
        
            // Hydrater l'objet manuellement
            $siteId = $this->extractIdFromIri($siteIri);
            $typeId = $this->extractIdFromIri($typeIri);
            $userId = $this->extractIdFromIri($demandeurIri);
        
            $site = $this->siteRepo->find($siteId);
            $type = $this->entityManager->getReference('App\Entity\TypeDemande', $typeId);
            $user = $this->entityManager->getReference('App\Entity\User', $userId);
        
            $data->setSite($site);
            $data->setType($type);
            $data->setDemandeur($user);
            $data->setStatut($statut);
            $data->setObjet($objet);
            $data->setContenu($contenu);

            $newRef = $this->generateUniqueReference();
            $data->setReference($newRef);

            //gerer le setsepartement

            $departement = null;
            $minRang = null;

            $deps = $this->depRepo->findBySite($site);

            $rangsTab = [];

            foreach($deps as $dep) {
                $rangs = $this->depRangRepo->findByTypeAndDepartement($type, $dep);
                $rangsTab = array_merge($rangsTab, $rangs);
            }

            $rangTab = [];

            foreach ($rangsTab as $rang) {
                $rangTab[] = $rang->getRang();

                if ($rang->getTypeDemande()->getId() === $type->getId()) {
                    if ($minRang === null || $rang->getRang() < $minRang) {
                        $minRang = $rang->getRang();
                        $departement = $rang->getDepartement();
                    }
                }
            }

            $data->setDepartement($departement);
        
        
            if ($fichier) {
                $nomFichier = uniqid() . '-' . $fichier->getClientOriginalName();
                $uploadDir = $this->params->get('kernel.project_dir') . "/public/uploads/demande_site_" . $site->getId();
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fichier->move($uploadDir, $nomFichier);
                $data->setFichier($nomFichier);
            }
        
            $data->setCreatedAt(new \DateTime());
            
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        
            return $data;
        }
   
    }

    private function extractIdFromIri(?string $iri): ?int
    {
        if (!$iri) return null;
        if (preg_match('#/(\d+)$#', $iri, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function generateUniqueReference(): string
    {
        $alphabet = range('A', 'Z');

        do {
            // Générer 5 chiffres
            $numbers = [];
            for ($i = 0; $i < 5; $i++) {
                $numbers[] = (string) random_int(0, 9);
            }

            // Générer 5 lettres
            $letters = [];
            for ($i = 0; $i < 5; $i++) {
                $letters[] = $alphabet[random_int(0, 25)];
            }

            // Mélanger chiffres et lettres
            $chars = array_merge($numbers, $letters);
            shuffle($chars);

            $newRef = implode('', $chars);

            // Vérifier que cette référence n'existe pas déjà
            $exists = $this->demandeRepo->findOneBy(['reference' => $newRef]);

        } while ($exists);

        return $newRef;
    }

}