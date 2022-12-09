<?php

namespace App\Solvers;

class Day03_Rucksack
{

    const DATA_PATH = __DIR__ . '/../assets/03-rucksacks.txt';

    public function getTotalPriorityOfItemsInBothCompartments(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $totalPriority = 0;
        while (($line = fgets($handle)) !== false) {
            $rucksack = trim($line);
            $compartments = $this->getCompartments($rucksack);
            $item = $this->findCommonItem($compartments);
            $totalPriority += $this->getItemPriority($item);
        }
        return $totalPriority;
    }

    public function getTotalPriorityInGroupsOfThree(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $totalPriority = 0;

        while (true) {
            $rucksacks = $this->getNext3Rucksacks($handle);
            if (!$rucksacks[0]) break;
            $item = $this->findCommonItem($rucksacks);
            $totalPriority += $this->getItemPriority($item);
        }

        return $totalPriority;
    }

    private function getNext3Rucksacks($handle): array
    {
        return [
            trim(fgets($handle)),
            trim(fgets($handle)),
            trim(fgets($handle))
        ];
    }

    private function findCommonItem(array $containers): string
    {
        $containers = array_map(array($this,'getDistinctItemsInContainer'), $containers);
        $commonItems = $containers[0];
        for ($i = 1; $i < count($containers); $i++) {
            $commonItems = array_intersect($commonItems, $containers[$i]);
        }
        return implode("", $commonItems);
    }

    private function getDistinctItemsInContainer(string $container)
    {
        return array_unique(str_split($container));
    }

    private function getCompartments(string $rucksack): array
    {
        $length = strlen($rucksack);
        return [
            substr($rucksack, 0, $length / 2),
            substr($rucksack, $length / 2)
        ];
    }

    private function getItemPriority(string $item): int
    {
        if (strtoupper($item) == $item) {
            return ord($item) - ord('A') + 27;
        }
        return ord($item) - ord('a') + 1;
    }
}
