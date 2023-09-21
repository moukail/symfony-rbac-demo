<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\CLImate\CLImate;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    public function __construct
    (
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher,
        private CLImate $climate,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputOption('email', null,InputOption::VALUE_REQUIRED, 'The email'),
                new InputOption('password', null,InputOption::VALUE_REQUIRED, 'The password'),
                new InputOption('first_name', null, InputOption::VALUE_REQUIRED, 'First name'),
                new InputOption('last_name', null,InputOption::VALUE_REQUIRED, 'Last name'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Admin Creator',
            '============',
            '',
        ]);

        $io = new SymfonyStyle($input, $output);

        /** @var string $email */
        $email = $input->getOption('email');
        if ($email === null) {
            $email = $this->climate->input('Email:')->prompt();
        }

        $password = $input->getOption('password');
        if ($password === null) {
            $password = $this->climate->password('Password:')->prompt();
        }

        $firstName = $input->getOption('first_name');
        if ($firstName === null) {
            $firstName = $this->climate->input('First name:')->prompt();
        }

        $lastName = $input->getOption('last_name');
        if ($lastName === null) {
            $lastName = $this->climate->input('Last name:')->prompt();
        }

        $inactive = $input->getOption('inactive');

        if ($email) {
            $io->note(sprintf('You passed an argument: %s', $email));
        }

        $password_hash = $this->passwordHasher->hashPassword(new User(), $password);

        // todo use CLIMate to select role
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => Role::ROLE_ADMIN]);

/*        $user = $this->serializer->deserialize([
            'email' => $email,
            'password' => $password_hash,
            'active' => true,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ], User::class,'json');*/

        $user = (new User())
            ->setEmail($email)
            ->setPassword($password_hash)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setInactive(false)
        ;

        $user->setRole($role);

        $errors = $this->validator->validate($user);

        if ($errors->count() > 0){
            $io->error('errors');
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Success');

        return Command::SUCCESS;
    }
}
