<?php

namespace App\Service;

use App\Entity\Privilege;
use App\Entity\CodeCouleur;
use App\Entity\TypeDemande;
use Symfony\Component\Yaml\Yaml;
use App\Entity\DomaineEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use App\Entity\CategorieDomaineEntreprise;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\DomaineEntrepriseRepository;
use App\Repository\CategorieDomaineEntrepriseRepository;

class DefaultsLoader
{
    private $em;
    private $categorieDomaineRepo;
    private $domaineEntrepriseRepo;

    public function __construct(
        EntityManagerInterface $em, 
        CategorieDomaineEntrepriseRepository $categorieDomaineRepo,
        DomaineEntrepriseRepository $domaineEntrepriseRepo
    )
    {
        $this->em = $em;
        $this->categorieDomaineRepo = $categorieDomaineRepo;
        $this->domaineEntrepriseRepo = $domaineEntrepriseRepo;
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
                $domaine = $this->domaineEntrepriseRepo->findOneBy(['id' => (int)$domaineId]);

                $type->setNom($content['nom']);
                $type->setLabel($label);
                $type->setDescription($content['description']);
                $date = new \datetime();
                $type->setCreatedAt($date);
                $type->addDomaine($domaine);

                $this->em->persist($type);
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
