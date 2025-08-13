<?php

namespace App\Command;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-entreprise',
    description: 'Créer une nouvelle entreprise',
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
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'entreprise');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nom = $input->getArgument('nom');
    
        // Vérifie si une entreprise existe déjà
        $existingEntreprise = $this->em->getRepository(Entreprise::class)->findOneBy([]);
        if ($existingEntreprise) {
            $output->writeln("<error>❌ Une entreprise existe déjà : '{$existingEntreprise->getNom()}'.</error>");
            return Command::FAILURE;
        }
    
        $entreprise = new Entreprise();
        $entreprise->setNom($nom);

        $this->em->persist($entreprise);
        $this->em->flush();

        $output->writeln("<info>✅ Entreprise '{$nom}' créée.</info>");

        return Command::SUCCESS;
    }
}
