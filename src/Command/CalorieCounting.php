<?php

namespace App\Command;

use App\Service\CalorieService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:countCalories',
    description: 'How much calories is carrying the elf carrying the most calories ?',
    hidden: false
)]
class CalorieCounting extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new CalorieService;
        // $maxCalories = $service->findMaxCalories();
        $maxCalories = $service->findMaxCaloriesFromTopThreeElves();
        $output->writeln("Max calories : $maxCalories");
        return Command::SUCCESS;
    }
}
