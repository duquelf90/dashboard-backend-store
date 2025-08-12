<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    private $entityManager;
        private $passwordEncoder;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', null, InputOption::VALUE_NONE, 'The username of the user')
            ->addArgument('password', null, InputOption::VALUE_NONE, 'The password of the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($username);
        $user->setUsername($username);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if ($username) {
            $io->note(sprintf('You passed an argument: %s', $username));
        }

        $io->success('User created successfully!');

        return Command::SUCCESS;
    }
}
