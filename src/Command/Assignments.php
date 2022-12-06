<?php

namespace App\Command;

use App\Service\CleanupAssignmentService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:assignments'
)]
class Assignments extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new CleanupAssignmentService;

        // $containedAssignments = $service->countFullyContainedAssignments();
        $containedAssignments = $service->countOverlappingAssignments();
        $output->writeln("Total : $containedAssignments");
        return Command::SUCCESS;
    }
}
