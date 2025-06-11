<?php

namespace App\Command;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Privilege;
use App\Entity\Entreprise;
use App\Entity\DomaineEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-site',
    description: 'Créer un nouveau site',
)]
class AddDefaultSiteCommand extends Command
{
    private EntityManagerInterface $em;
    private $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nom = $input->getArgument('nom');
    
        // Recherche du entreprise existant par ID
        $entreprise = $this->em->getRepository(Entreprise::class)->find(1);
    
        if (!$entreprise) {
            $output->writeln("<error>❌ DomaineEntreprise avec l'ID '$entrepriseId' introuvable.</error>");
            return Command::FAILURE;
        }

        $existingSite = $this->em->getRepository(Site::class)->findOneBy(['isCurrent' => true]);

        if($existingSite) {
            $output->writeln("<info>✅ Un site qui a isCurrent existe déjà.</info>");
            return Command::FAILURE;
        } else {
            $site = new Site();

            $site->setNom($nom);
            $site->setIsCurrent(true);
            $site->setEntreprise($entreprise);
        
            $this->em->persist($site);
            $this->em->flush();
        
            $output->writeln("<info>✅ Site '{$nom}' créée avec le entreprise '{$entreprise->getNom()}'.</info>");
            return Command::SUCCESS;
        }
    
       
    }
    
}
