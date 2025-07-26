<?php

namespace App\Service;

use App\Entity\Langue;
use App\Entity\Service;
use App\Entity\Tutoriel;
use App\Entity\Privilege;
use App\Entity\CodeCouleur;
use App\Entity\Departement;
use App\Entity\HeroSection;
use App\Entity\TypeDemande;
use App\Entity\AboutSection;
use App\Entity\DossierAFournir;
use Symfony\Component\Yaml\Yaml;
use App\Entity\DomaineEntreprise;
use App\Entity\NiveauHierarchique;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use App\Repository\DepartementRepository;
use App\Repository\TypeDemandeRepository;
use App\Entity\CategorieDomaineEntreprise;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\DomaineEntrepriseRepository;
use App\Repository\CategorieDomaineEntrepriseRepository;

class DefaultsLoader
{
    private $em;
    private $categorieDomaineRepo;
    private $domaineEntrepriseRepo;
    private $typeDemandeRepo;
    private $siteRepo;
    private $departementRepo;

    public function __construct(
        EntityManagerInterface $em, 
        CategorieDomaineEntrepriseRepository $categorieDomaineRepo,
        DomaineEntrepriseRepository $domaineEntrepriseRepo,
        TypeDemandeRepository $typeDemandeRepo,
        SiteRepository $siteRepo,
        DepartementRepository $departementRepo
    )
    {
        $this->em = $em;
        $this->categorieDomaineRepo = $categorieDomaineRepo;
        $this->domaineEntrepriseRepo = $domaineEntrepriseRepo;
        $this->typeDemandeRepo = $typeDemandeRepo;
        $this->siteRepo = $siteRepo;
        $this->departementRepo = $departementRepo;
    }

    private function maybeCreate($class, $criteria, ?string $repositoryMethodName = 'findOneBy'): array
    {
        $entity = $this->em->getRepository($class)->{$repositoryMethodName}($criteria);
        $isNew = is_null($entity);
        if ($isNew) {
            $entity = new $class;
        }
        return [$isNew, $entity];
    }

    public function loadDb()
    {
        $this->categorieDomaineEntreprise();
        $this->domaineEntreprise();
        $this->privilege();
        $this->CodeCouleur();
        $this->typeDemandes();
        $this->dossiers();
        $this->heroSections();
        $this->langues();
        $this->aboutSections();
        $this->services();
        $this->tutoriels();
        $this->departements();
        $this->niveauHierarchiques();
        $this->copyFiles();

    }

    public function categorieDomaineEntreprise() {
        $categories = Yaml::parseFile('defaults/data/categorie_domaine_entreprise.yaml');

        foreach ($categories as $label => $content) {
            list($isNew, $category) = $this->maybeCreate(CategorieDomaineEntreprise::class, ['label' => $label]);
            if($isNew){
                $category->setNom($content['nom']);
                $category->setNomEn($content['nomEn']);
                $category->setLabel($label);
                $category->setDescription($content['description']);
                $category->setDescriptionEn($content['descriptionEn']);
                $date = new \datetime();
                $category->setCreatedAt($date);

                $this->em->persist($category);
                $this->em->flush();
            }
        }
    }

    public function domaineEntreprise() {
        $domaines = Yaml::parseFile('defaults/data/domaine_entreprise.yaml');

        foreach ($domaines as $label => $content) {
            list($isNew, $domaine) = $this->maybeCreate(DomaineEntreprise::class, ['label' => $label]);
            if($isNew){
                $domaine->setLibelle($content['nom']);
                $domaine->setLibelleEn($content['nomEn']);
                $domaine->setLabel($label);
                $domaine->setDescription($content['description']);
                $domaine->setDescriptionEn($content['descriptionEn']);
                $categoryId = $content['categoryId'];
                $category = $this->categorieDomaineRepo->findOneBy(['id' => $categoryId]);
                $domaine->setCategorieDomaineEntreprise($category);
                $date = new \datetime();
                $domaine->setCreatedAt($date);

                $this->em->persist($domaine);
                $this->em->flush();
            }
        }
    }

    public function privilege() {
        $privileges = Yaml::parseFile('defaults/data/privilege.yaml');

        foreach ($privileges as $label => $content) {
            list($isNew, $privilege) = $this->maybeCreate(Privilege::class, ['label' => $label]);
            if($isNew){
                $privilege->setTitle($content['title']);
                $privilege->setLabel($label);
                $privilege->setDescription($content['description']);
                $privilege->setDescriptionEn($content['descriptionEn']);
                $privilege->setLibelleFr($content['libelleFr']);
                $privilege->setLibelleEn($content['libelleEn']);
                $date = new \datetime();
                $privilege->setCreatedAt($date);

                $this->em->persist($privilege);
                $this->em->flush();
            }
        }
    }

    public function CodeCouleur() {
        $codeCouleurs = Yaml::parseFile('defaults/data/codeCouleur.yaml');

        foreach ($codeCouleurs as $label => $content) {
            list($isNew, $codeCouleur) = $this->maybeCreate(CodeCouleur::class, ['label' => $label]);
            if($isNew){
                $codeCouleur->setLabel($label);
                $codeCouleur->setBgColor($content['bgColor']);
                $codeCouleur->setTextColor($content['textColor']);
                $codeCouleur->setBtnColor($content['btnColor']);
                $codeCouleur->setTextColorHover($content['textColorHover']);
                $codeCouleur->setBtnColorHover($content['btnColorHover']);
                $codeCouleur->setColorOne($content['color1']);
                $codeCouleur->setColorTwo($content['color2']);
                $codeCouleur->setIsDefault($content['isDefault']);
                $codeCouleur->setIsGlobal($content['isGlobal']);
                $codeCouleur->setLibelle($content['libelle']);
                $codeCouleur->setIsActive($content['isActive']);
                $date = new \datetime();
                $codeCouleur->setCreatedAt($date);

                $this->em->persist($codeCouleur);
                $this->em->flush();
            }
        }
    }


    public function typeDemandes() {
        $typeDemandes = Yaml::parseFile('defaults/data/type_demande.yaml');

        foreach ($typeDemandes as $label => $content) {
            list($isNew, $type) = $this->maybeCreate(TypeDemande::class, ['label' => $label]);
            if($isNew){
                $domaineId = $content['domaineId'];
                $domaine = $this->domaineEntrepriseRepo->findOneBy(['id' => $domaineId]);
                $siteIds = $content['siteId'];
                foreach($siteIds as $siteId) {
                    $site = $this->siteRepo->findOneBy(['id' => $siteId]);
                    $type->addSite($site);
                }

                $type->setNom($content['nom']);
                $type->setNomEn($content['nomEn']);
                $type->setLabel($label);
                $type->setDescription($content['description']);
                $type->setDescriptionEn($content['descriptionEn']);
                $date = new \datetime();
                $type->setCreatedAt($date);
                $type->setDomaine($domaine);
                $type->setIsActive(true);

                $this->em->persist($type);
                $this->em->flush();
            }
        }
    }

    public function dossiers() {
        $dossiers = Yaml::parseFile('defaults/data/dossier_a_fournir.yaml');

        foreach ($dossiers as $label => $content) {
            list($isNew, $dossier) = $this->maybeCreate(DossierAFournir::class, ['label' => $label]);
            if($isNew){
                $typeDemandeIds = $content['idTypeDemande'];

                foreach($typeDemandeIds as $typeId) {
                    $typeDemande = $this->typeDemandeRepo->findOneBy(['id' => $typeId]);
                    if ($typeDemande) {
                        $dossier->addTypeDemande($typeDemande);
                    } else {
                        // Facultatif : log ou throw si type introuvable
                        // throw new \Exception("TypeDemande avec ID $typeId introuvable.");
                    }
                }

                $dossier->setTitle($content['title']);
                $dossier->setTitleEn($content['titleEn']);
                $dossier->setLabel($label);
                $date = new \datetime();
                $dossier->setCreatedAt($date);

                $this->em->persist($dossier);
                $this->em->flush();
            }
        }
    }

    public function heroSections() {
        $heroSections = Yaml::parseFile('defaults/data/hero_section.yaml');

        foreach ($heroSections as $label => $content) {
            list($isNew, $hero) = $this->maybeCreate(HeroSection::class, ['label' => $label]);
            if($isNew){
                $hero->setTitleFr($content['titleFr']);
                $hero->setTitleEn($content['titleEn']);
                $hero->setLabel($label);
                $hero->setDescriptionFr($content['descriptionFr']);
                $hero->setDescriptionEn($content['descriptionEn']);
                $hero->setBgImage($content['bgImage']);

                $this->em->persist($hero);
                $this->em->flush();
            }
        }
    }

    public function langues() {
        $langues = Yaml::parseFile('defaults/data/langue.yaml');

        foreach ($langues as $label => $content) {
            list($isNew, $langue) = $this->maybeCreate(Langue::class, ['label' => $label]);
            if($isNew){
                $langue->setTitleFr($content['titleFr']);
                $langue->setTitleEn($content['titleEn']);
                $langue->setLabel($label);
                $langue->setIcon($content['icon']);
                $langue->setIsActive($content['active']);
                $langue->setIndice($content['indice']);

                $this->em->persist($langue);
                $this->em->flush();
            }
        }
    }

    public function aboutSections() {
        $aboutSections = Yaml::parseFile('defaults/data/about_section.yaml');

        foreach ($aboutSections as $label => $content) {
            list($isNew, $about) = $this->maybeCreate(AboutSection::class, ['label' => $label]);
            if($isNew){
                $about->setTitleFr($content['titleFr']);
                $about->setTitleEn($content['titleEn']);
                $about->setLabel($label);
                $about->setDescriptionFr($content['descriptionFr']);
                $about->setDescriptionEn($content['descriptionEn']);

                $this->em->persist($about);
                $this->em->flush();
            }
        }
    }
    
    public function services() {
        $services = Yaml::parseFile('defaults/data/service.yaml');

        foreach ($services as $label => $content) {
            list($isNew, $service) = $this->maybeCreate(Service::class, ['label' => $label]);
            if($isNew){
                $service->setTitleFr($content['titleFr']);
                $service->setTitleEn($content['titleEn']);
                $service->setLabel($label);
                $service->setIcon($content['icon']);
                $service->setNumber($content['number']);
                $service->setIsActive(true);
                $service->setCreatedAt(new \DateTime());

                $this->em->persist($service);
                $this->em->flush();
            }
        }
    }

    public function tutoriels() {
        $tutoriels = Yaml::parseFile('defaults/data/tutoriel.yaml');

        foreach ($tutoriels as $label => $content) {
            list($isNew, $tutoriel) = $this->maybeCreate(Tutoriel::class, ['label' => $label]);
            if($isNew){
                $tutoriel->setTitleFr($content['titleFr']);
                $tutoriel->setTitleEn($content['titleEn']);
                $tutoriel->setDescriptionFr($content['descriptionFr']);
                $tutoriel->setDescriptionEn($content['descriptionEn']);
                $tutoriel->setLabel($label);
                $tutoriel->setIcon($content['icon']);
                $content['video'] ? $tutoriel->setVideo($content['video']) : null;
                $content['fichier'] ? $tutoriel->setFichier($content['fichier']) : null;
                $tutoriel->setIsActive(true);
                $tutoriel->setCreatedAt(new \DateTime());

                $this->em->persist($tutoriel);
                $this->em->flush();
            }
        }
    }

    public function departements() {
        $departements = Yaml::parseFile('defaults/data/departement.yaml');

        foreach ($departements as $label => $content) {
            list($isNew, $departement) = $this->maybeCreate(Departement::class, ['label' => $label]);
            if($isNew){
                $siteIds = $content['siteId'];
                foreach($siteIds as $siteId) {
                    $site = $this->siteRepo->findOneBy(['id' => $siteId]);
                    if (!$site) {
                        throw new \RuntimeException("Site introuvable avec l'ID : $siteId");
                    }
                    $departement->addSite($site);
                }
                $departement->setNom($content['nom']);
                $departement->setNomEn($content['nomEn']);
                $departement->setDescription($content['description']);
                $departement->setDescriptionEn($content['descriptionEn']);
                $departement->setLabel($label);
                $departement->setIsActive(true);
                $departement->setCreatedAt(new \DateTime());

                $this->em->persist($departement);

                $this->em->flush();
            }
        }
    }

    public function niveauHierarchiques() {
        $niveauHierarchiques = Yaml::parseFile('defaults/data/niveau_hierarchique.yaml');

        foreach ($niveauHierarchiques as $label => $content) {
            list($isNew, $niveau) = $this->maybeCreate(NiveauHierarchique::class, ['label' => $label]);
            if($isNew){
                $departements = $this->departementRepo->findBy(['isActive' => true]);
                foreach($departements as $departement) {
                    $niveau->addDepartement($departement);
                }
                $niveau->setNom($content['nom']);
                $niveau->setNomEn($content['nomEn']);
                $niveau->setDescription($content['description']);
                $niveau->setDescriptionEn($content['descriptionEn']);
                $niveau->setLabel($label);
                $niveau->setIsActive(true);
                $niveau->setCreatedAt(new \DateTime());

                $this->em->persist($niveau);
                $this->em->flush();
            }
        }
    }

    public function copyFiles()
    {
        $fs = new Filesystem();
        $fileDefs = Yaml::parseFile('defaults/files.yaml') ?? [];
        foreach ($fileDefs as $destDir => $fileMappings) {
            foreach ($fileMappings as $dest => $source) {
                $destFile = u('/')->join([u($destDir), $dest]);
                $fs->copy($source, $destFile);
            };
        };
    }
    

}
