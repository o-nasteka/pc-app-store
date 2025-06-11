<?php

namespace App\Command;

use App\Domain\Entity\Activity;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\ActivityType;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedAnalyticsCommand extends Command
{
    // protected static $defaultName = 'analytics:seed';

    public function __construct(
        private ActivityRepositoryInterface $activityRepository,
        private UserRepositoryInterface $userRepository
    ) {
        parent::__construct('analytics:seed');
    }

    protected function configure()
    {
        $this->setDescription('Seed random analytical activity data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();
        $types = [
            ActivityType::login(),
            ActivityType::logout(),
            ActivityType::registration(),
            ActivityType::viewPage(),
            ActivityType::buttonClick()
        ];
        $pages = ['page_a', 'page_b'];
        $buttons = ['buy_cow', 'download', null];

        for ($i = 0; $i < 100; $i++) {
            $user = $users[array_rand($users)];
            $type = $types[array_rand($types)];
            $page = $type->getValue() === 'view_page' ? $pages[array_rand($pages)] : null;
            $button = $type->getValue() === 'button_click' ? $buttons[array_rand($buttons)] : null;
            $activity = Activity::create(
                ActivityId::generate(),
                $user->getId(),
                $type,
                $page,
                $button,
                '127.0.0.1',
                'Seeder/1.0'
            );
            $this->activityRepository->save($activity);
            // Set a random created_at date within the last 30 days
            $randomTimestamp = (new \DateTimeImmutable())
                ->modify('-' . rand(0, 29) . ' days')
                ->setTime(rand(0, 23), rand(0, 59), rand(0, 59));
            $this->activityRepository->updateCreatedAt($activity->getId(), $randomTimestamp);
        }
        $output->writeln('<info>Seeded 100 random activities.</info>');
        return Command::SUCCESS;
    }
} 