<?php

namespace App\Command;

use App\Service\RockPaperScissorsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:RPS',
    description: 'Rock paper scissors',
    hidden: false
)]
class RPS extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new RockPaperScissorsService;
        // $score = $service->getScoreWithDeterminedChoices();
        $score = $service->getScoreWithDeterminedResult();
        $output->writeln("Score : $score");
        return Command::SUCCESS;
    }
}
