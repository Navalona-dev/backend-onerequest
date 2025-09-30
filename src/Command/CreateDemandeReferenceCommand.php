<?php

namespace App\Command;

use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-demande-ref',
    description: 'Créer une nouvelle référence de demande',
)]
class CreateDemandeReferenceCommand extends Command
{
    private EntityManagerInterface $em;
    private DemandeRepository $demandeRepo;

    public function __construct(
        EntityManagerInterface $em,
        DemandeRepository $demandeRepo
    )
    {
        parent::__construct();
        $this->em = $em;
        $this->demandeRepo = $demandeRepo;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $demandes = $this->demandeRepo->findBy(['reference' => null]);

        foreach($demandes as $demande) {
            $newRef = $this->generateUniqueReference();
            $demande->setReference($newRef);
        }

        $this->em->flush();

        $output->writeln("<info>✅ Références générées pour les demandes sans référence.</info>");

        return Command::SUCCESS;
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
