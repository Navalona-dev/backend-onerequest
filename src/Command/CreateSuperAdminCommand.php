<?php

namespace App\Command;

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
    name: 'app:create-super-admin',
    description: 'Créer un nouveau super admin avec son domaine',
)]
class CreateSuperAdminCommand extends Command
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
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'utilisateur')
            ->addArgument('prenom', InputArgument::REQUIRED, 'Prénom de l\'utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('privilege_id', InputArgument::REQUIRED, 'ID du privilege de l\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $email = $input->getArgument('email');
        $privilegeId = $input->getArgument('privilege_id');
    
        // Recherche du privilege existant par ID
        $privilege = $this->em->getRepository(Privilege::class)->find($privilegeId);
    
        if (!$privilege) {
            $output->writeln("<error>❌ DomaineEntreprise avec l'ID '$privilegeId' introuvable.</error>");
            return Command::FAILURE;
        }
    
        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password12345');

        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setIsSuperAdmin(true);
        $user->setRoles(["ROLE_USER"]);
        $user->addPrivilege($privilege);
    
        $this->em->persist($user);
        $this->em->flush();
    
        $output->writeln("<info>✅ Utilisateur '{$nom}' créée avec le privilege '{$privilege->getTitle()}'.</info>");
        return Command::SUCCESS;
    }
    
}
