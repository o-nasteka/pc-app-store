<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\UserRole;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected static $defaultDescription = 'Creates an admin user';

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Creates an admin user')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Admin email', 'admin@example.com')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Admin password', 'admin123')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Admin name', 'Administrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $name = $input->getOption('name');

        try {
            // Check if admin already exists
            $existingUser = $this->userRepository->findByEmail(new Email($email));

            if ($existingUser) {
                $io->warning('Admin user already exists with email: ' . $email);
                return Command::SUCCESS;
            }

            // Create admin user
            $user = User::create(
                new Email($email),
                Password::fromPlainText($password),
                $name,
                UserRole::admin()
            );

            $this->userRepository->save($user);

            $io->success('Admin user created successfully!');
            $io->table(
                ['Field', 'Value'],
                [
                    ['Email', $email],
                    ['Password', $password],
                    ['Name', $name],
                    ['Role', 'admin'],
                ]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Failed to create admin user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
