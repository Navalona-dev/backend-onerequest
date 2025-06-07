<?php

namespace App\Command;

use App\Entity\Entreprise;
use App\Entity\DomaineEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-entreprise',
    description: 'Créer une nouvelle entreprise avec son domaine',
)]
class CreateEntrepriseCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'entreprise')
            ->addArgument('domaine_id', InputArgument::REQUIRED, 'ID du domaine de l\'entreprise');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nom = $input->getArgument('nom');
        $domaineId = $input->getArgument('domaine_id');
    
        // Vérifie si une entreprise existe déjà
        $existingEntreprise = $this->em->getRepository(Entreprise::class)->findOneBy([]);
    
        if ($existingEntreprise) {
            $output->writeln("<error>❌ Une entreprise existe déjà : '{$existingEntreprise->getNom()}'. Vous ne pouvez pas en créer une autre.</error>");
            return Command::FAILURE;
        }
    
        // Recherche du domaine existant par ID
        $domaine = $this->em->getRepository(DomaineEntreprise::class)->find($domaineId);
    
        if (!$domaine) {
            $output->writeln("<error>❌ DomaineEntreprise avec l'ID '$domaineId' introuvable.</error>");
            return Command::FAILURE;
        }
    
        $entreprise = new Entreprise();
        $entreprise->setNom($nom);
        $entreprise->setDomaineEntreprise($domaine);
    
        $this->em->persist($entreprise);
        $this->em->flush();
    
        $output->writeln("<info>✅ Entreprise '{$nom}' créée avec le domaine '{$domaine->getLibelle()}'.</info>");
        return Command::SUCCESS;
    }
    
}
