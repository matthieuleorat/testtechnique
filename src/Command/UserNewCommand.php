<?php

declare(strict_types=1);

namespace App\Command;

use App\User\Command\CreateUserCommand;
use App\User\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:new',
    description: 'Create a new user in the application',
)]
class UserNewCommand extends Command
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

        $questionRole = new ChoiceQuestion(
            'Please select a user role (default to member)',
            // choices can also be PHP objects that implement __toString() method
            ['ROLE_MEMBER', 'ROLE_ADMIN'],
            0
        );
        $questionRole->setErrorMessage('Role %s is invalid.');
        $role = $helper->ask($input, $output, $questionRole);


        $questionPassword = new Question('Please provide the user password: ', false);
        $questionPassword->setHidden(true);
        $questionPassword->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $questionPassword);

        try {
            $createUserCommand = new CreateUserCommand($username, $password, $role);
            $this->userService->createUser($createUserCommand);
            $io->success("The user {$username} has been created");
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
