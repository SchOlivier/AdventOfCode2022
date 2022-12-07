<?php

namespace App\Solvers;

use App\Entity\RearrangementInstruction;

class Day05_Stacks
{

    const DATA_PATH = __DIR__ . '/../assets/05-stacks.txt';
    const MAX_HEIGHT = 8;
    const NUMBER_OF_STACKS = 9;

    public function getTopCratesAfterRearrangement9000(): string
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $stacks = $this->getInitialStacks($handle);
        while (($line = fgets($handle)) !== false) {
            $instruction = $this->getInstruction($line);
            $this->executeInstruction9000($instruction, $stacks);
        }

        return $this->getTopCrates($stacks);
    }

    public function getTopCratesAfterRearrangement9001(): string
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $stacks = $this->getInitialStacks($handle);
        while (($line = fgets($handle)) !== false) {
            $instruction = $this->getInstruction($line);
            $this->executeInstruction9001($instruction, $stacks);
        }

        return $this->getTopCrates($stacks);
    }

    private function executeInstruction9000(RearrangementInstruction $instruction, array &$stacks): void
    {
        for ($i = 0; $i < $instruction->numberOfMoves; $i++) {
            $stacks[$instruction->to][] = array_pop($stacks[$instruction->from]);
        }
    }

    private function executeInstruction9001(RearrangementInstruction $instruction, array &$stacks): void
    {
        $movedCrates = array_slice($stacks[$instruction->from], -$instruction->numberOfMoves);
        $stacks[$instruction->from] = array_slice($stacks[$instruction->from], 0, -$instruction->numberOfMoves);
        $stacks[$instruction->to] = array_merge($stacks[$instruction->to], $movedCrates);
    }

    private function getInstruction(string $line): RearrangementInstruction
    {
        $regex = '/move (\d+) from (\d+) to (\d+)/';
        preg_match($regex, $line, $matches);
        return new RearrangementInstruction(
            numberOfMoves: $matches[1],
            from: $matches[2] - 1,
            to: $matches[3] - 1
        );
    }

    private function getTopCrates(array $stacks): string
    {
        $topCrates = '';
        foreach ($stacks as $stack) {
            $topCrates .= array_pop($stack);
        }
        return $topCrates;
    }

    private function getInitialStacks(mixed $handle): array
    {
        // La flemme de compter le nombre de colonnes et de définir une condition d'arret.
        $stacks = [];
        $stacks = array_fill(0, self::NUMBER_OF_STACKS, []);

        for ($i = 0; $i < self::MAX_HEIGHT; $i++) {
            $line = fgets($handle);
            $splitLine = str_split($line, 4);

            foreach ($splitLine as $j => $crate) {
                $crate = trim($crate);
                if ($crate != '') {
                    array_unshift($stacks[$j], $crate[1]);
                }
            }
        }

        // Placing the cursor at the beginning of the rearrangement instructions
        fgets($handle);
        fgets($handle);

        return $stacks;
    }
}
