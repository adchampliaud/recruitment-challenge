<?php

declare(strict_types=1);

namespace App\UserInterface\Cli;

use App\Core\Application\Capital\CapitalByCountryMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CapitalSendMessageConsoleCommand extends Command
{
    private const string CONSOLE_COMMAND_NAME = 'app:capital:send-message';
    private const string COUNTRY_CODE_ARGUMENT_NAME = 'country-code';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setName(self::CONSOLE_COMMAND_NAME)
            ->addArgument(
                self::COUNTRY_CODE_ARGUMENT_NAME,
                InputArgument::REQUIRED,
                'Country code',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->messageBus->dispatch(new CapitalByCountryMessage($input->getArgument(self::COUNTRY_CODE_ARGUMENT_NAME)));

        return self::SUCCESS;
    }
}
