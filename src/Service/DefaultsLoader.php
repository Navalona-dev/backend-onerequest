<?php

namespace App\Service;

use App\Entity\Langue;
use App\Entity\Privilege;
use App\Entity\CodeCouleur;
use App\Entity\HeroSection;
use App\Entity\TypeDemande;
use App\Entity\DossierAFournir;
use Symfony\Component\Yaml\Yaml;
use App\Entity\DomaineEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
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

    public function __construct(
        EntityManagerInterface $em, 
        CategorieDomaineEntrepriseRepository $categorieDomaineRepo,
        DomaineEntrepriseRepository $domaineEntrepriseRepo,
        TypeDemandeRepository $typeDemandeRepo
    )
    {
        $this->em = $em;
        $this->categorieDomaineRepo = $categorieDomaineRepo;
        $this->domaineEntrepriseRepo = $domaineEntrepriseRepo;
        $this->typeDemandeRepo = $typeDemandeRepo;
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
        $this->copyFiles();

    }

    public function categorieDomaineEntreprise() {
        $categories = Yaml::parseFile('defaults/data/categorie_domaine_entreprise.yaml');

        foreach ($categories as $label => $content) {
            list($isNew, $category) = $this->maybeCreate(CategorieDomaineEntreprise::class, ['label' => $label]);
            if($isNew){
                $category->setNom($content['nom']);
                $category->setLabel($label);
                $category->setDescription($content['description']);
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
                $domaine->setLabel($label);
                $domaine->setDescription($content['description']);
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

                $type->setNom($content['nom']);
                $type->setLabel($label);
                $type->setDescription($content['description']);
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
