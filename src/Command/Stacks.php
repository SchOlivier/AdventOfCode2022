<?php

namespace App\Command;

use App\Service\StacksService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:stacks'
)]
class Stacks extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new StacksService;

        // $topCrates = $service->getTopCratesAfterRearrangement9000();
        $topCrates = $service->getTopCratesAfterRearrangement9001();
        $output->writeln("top crates : $topCrates");
        return Command::SUCCESS;
    }
}
