<?php

namespace App\Command;

use App\Service\DatastreamService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'advent:datastream'
)]
class Datastream extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = new DatastreamService;

        // $index = $service->getIndexOfStartOfPacket();
        $index = $service->getIndexOfStartOfMessage();
        $output->writeln("index : $index");
        return Command::SUCCESS;
    }
}
