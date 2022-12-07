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
            list($firstCompartment, $secondCompartment) = $this->getCompartments($rucksack);
            $item = $this->findItemInBothCompartments($firstCompartment, $secondCompartment);
            $totalPriority += $this->getItemPriority($item);
        }
        return $totalPriority;
    }

    public function getTotalPriorityInGroupsOfThree(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $totalPriority = 0;

        while (true) {
            list($rucksack1, $rucksack2, $rucksack3) = $this->getNext3Rucksacks($handle);
            if(!$rucksack1) break;
            $item = $this->getCommonItemInThreeRucksacks($rucksack1, $rucksack2, $rucksack3);
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

    private function getCommonItemInThreeRucksacks(string $rucksack1, string $rucksack2, string $rucksack3): string
    {
        $items2 = $this->getLettersInCompartment($rucksack2);
        $items3 = $this->getLettersInCompartment($rucksack3);
        foreach(str_split($rucksack1) as $item){
            if(isset($items2[$item]) && isset($items3[$item])) return $item;
        }
        return null;
    }

    private function findItemInBothCompartments(string $firstCompartment, string $secondCompartment): string
    {
        
        $items = $this->getLettersInCompartment($firstCompartment);
        foreach (str_split($secondCompartment) as $item) {
            if (isset($items[$item])) {
                return $item;
            }
        }
        return '';
    }

    private function getLettersInCompartment(string $compartment): array
    {
        return array_flip(str_split($compartment));
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
        if ($this->isUpperCase($item)) {
            return ord($item) - ord('A') + 27;
        }
        return ord($item) - ord('a') + 1;
    }

    private function isUpperCase(string $char): bool
    {
        return strtoupper($char) == $char;
    }
}
