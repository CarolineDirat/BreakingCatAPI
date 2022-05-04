<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\DeleteHomeCards;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-homeCards',
    description: 'Delete /public/homeCards directory',
)]
class DeleteHomeCardsCommand extends Command
{
    public function __construct(private DeleteHomeCards $deleteHomeCards)
    {
        $this->{$deleteHomeCards} = $deleteHomeCards;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $deletions = $this->deleteHomeCards->delete();
            $output->writeln('You\'ve been deleting the public/homeCards directory');
            $output->write('Number of directories deleted: ' . $deletions);

            return Command::SUCCESS;
        } catch (\Throwable $throwable) {
            $output->write($throwable->getMessage());

            return Command::FAILURE;
        }
    }
}
