<?php

namespace App\Command;

use App\Service\RucksackService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:rucksack',
    description: 'rucksack',
    hidden: false
)]
class Rucksack extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new RucksackService;

        // $totalPriority = $service->getTotalPriorityOfItemsInBothCompartments();
        $totalPriority = $service->getTotalPriorityInGroupsOfThree();
        $output->writeln("Priorité : $totalPriority");
        return Command::SUCCESS;
    }
}
