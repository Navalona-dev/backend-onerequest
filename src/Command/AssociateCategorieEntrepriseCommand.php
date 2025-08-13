<?php

namespace App\Command;

use App\Entity\Entreprise;
use App\Entity\CategorieDomaineEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:associate-categorie-entreprise',
    description: 'Associer une ou plusieurs catégories de domaine à une ou plusieurs entreprises',
)]
class AssociateCategorieEntrepriseCommand extends Command
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
            ->addArgument('entrepriseIds', InputArgument::REQUIRED, 'IDs des entreprises séparés par une virgule (ex: 1,2)')
            ->addArgument('categorieIds', InputArgument::REQUIRED, 'IDs des catégories séparés par une virgule (ex: 1,3)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entrepriseIds = explode(',', $input->getArgument('entrepriseIds'));
        $categorieIds = explode(',', $input->getArgument('categorieIds'));

        foreach ($entrepriseIds as $entrepriseId) {
            $entreprise = $this->em->getRepository(Entreprise::class)->find($entrepriseId);
            if (!$entreprise) {
                $output->writeln("<comment>⚠️ Entreprise avec l'ID {$entrepriseId} introuvable, ignorée.</comment>");
                continue;
            }

            foreach ($categorieIds as $categorieId) {
                $categorie = $this->em->getRepository(CategorieDomaineEntreprise::class)->find($categorieId);
                if (!$categorie) {
                    $output->writeln("<comment>⚠️ Catégorie avec l'ID {$categorieId} introuvable, ignorée.</comment>");
                    continue;
                }

                // Ajoute la catégorie à l'entreprise
                $entreprise->addCategorieDomaineEntreprise($categorie);
            }

            $this->em->persist($entreprise);
        }

        $this->em->flush();

        $output->writeln("<info>✅ Association des catégories aux entreprises terminée.</info>");
        return Command::SUCCESS;
    }
}
