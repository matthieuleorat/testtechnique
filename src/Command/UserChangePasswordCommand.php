<?php

declare(strict_types=1);

namespace App\Command;

use App\User\Command\ChangePasswordCommand;
use App\User\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:change-password',
    description: 'Change the password for a user',
)]
class UserChangePasswordCommand extends Command
{
    public function __construct(private readonly UserService $userService, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        $helper = $this->getHelper('question');

        $question = new Question('Please provide the new user password: ', false);
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $question);

        try {
            $changePasswordCommand = new ChangePasswordCommand($username, $password);
            $this->userService->changePassword($changePasswordCommand);
            $io->success("The password for user {$username} has been reset");
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
