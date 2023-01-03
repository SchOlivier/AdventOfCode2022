<?php

namespace App\Solvers;

use App\Entity\Position;

class Day23_diffusion
{
    public array $directions = ['N', 'S', 'W', 'E'];
    public array $elves;
    public int $minRow, $maxRow, $minColumn, $maxColumn;

    public function partOne()
    {
        $this->readInput();
        $this->displayElves();
        $i = 0;
        while($this->playARound()){
            $i++;
            echo "\nRound $i :\n";
            // $this->displayElves(); 
            if($i == 10) break;
        }
        $this->displayElves(); 

        $count = 0;
        for ($i = $this->minRow; $i <= $this->maxRow; $i++) {
            for ($j = $this->minColumn; $j <= $this->maxColumn; $j++) {
                if(!isset($this->elves[$i][$j])) $count++;
            }
        }

        echo "Number of empty tiles : $count\n";
    }

    public function partTwo()
    {
        $this->readInput();
        $this->displayElves();
        $i = 0;
        while($this->playARound()){
            $i++;
            echo "Round $i :\n";
            // $this->displayElves(); 
        }


        echo "Number of needed rounds : " . $i + 1 . "\n";
    }

    private function readInput()
    {
        $this->minRow = $this->minColumn = PHP_INT_MAX;
        $this->maxRow = $this->maxColumn = PHP_INT_MIN;
        $file = fopen('assets/23-elves', 'r');
        $i = 0;
        while (($row = fgets($file)) !== false) {
            foreach (str_split(trim($row)) as $j => $c) {
                if ($c == '#') {
                    $this->elves[$i][$j] = false;
                    $this->minRow = min($this->minRow, $i);
                    $this->maxRow = max($this->maxRow, $i);
                    $this->minColumn = min($this->minColumn, $j);
                    $this->maxColumn = max($this->maxColumn, $j);
                }
            }
            $i++;
        }
    }

    private function playARound():bool
    {
        $moveList = [];

        foreach ($this->elves as $row => $elves) {
            foreach ($elves as $column => $v) {
                // echo "Elf at $row, $column  : \n";
                if ($this->isAlone($row, $column)) {
                    // echo "\t Alone, not moving.\n";
                    $this->elves[$row][$column] = false;
                    continue;
                }
                $move = $this->considerMove($row, $column);
                $this->elves[$row][$column] = $move;
                if ($move) {
                    $moveList[$move] = isset($moveList[$move]) ? $moveList[$move] + 1 : 1;
                    // echo "\tmovement planed to "  . $move . "\n";
                } else {
                    // echo "\tNo move available\n";
                }
            }
        }

        // echo "before cleanup : \n";
        // print_r($moveList);
        $moveList = array_filter($moveList, function ($a) {
            return $a <= 1;
        });
        if(empty($moveList)) return false;
        // echo "after cleanup : \n";
        // print_r($moveList);

        foreach ($this->elves as $row => $elves) {
            foreach ($elves as $column => $nextPosition) {
                // echo "Elf at $row, $column  : \n";
                if ($nextPosition == false){
                    // echo "\tnot moving\n";
                    continue;
                }
                if (isset($moveList[$nextPosition])) {
                    // echo "\tmoving to $nextPosition\n";
                    unset($this->elves[$row][$column]);
                    $nextPosition = explode('_', $nextPosition);
                    $this->elves[$nextPosition[0]][$nextPosition[1]] = false;

                    $this->minRow = min($this->minRow, $nextPosition[0]);
                    $this->maxRow = max($this->maxRow, $nextPosition[0]);
                    $this->minColumn = min($this->minColumn, $nextPosition[1]);
                    $this->maxColumn = max($this->maxColumn, $nextPosition[1]);
                }
            }
        }

        $this->directions[] = array_shift($this->directions);
        return true;
    }

    private function isAlone(int $row, int $column)
    {
        if (
            isset($this->elves[$row][$column + 1])
            || isset($this->elves[$row][$column - 1])
            || isset($this->elves[$row + 1][$column + 1])
            || isset($this->elves[$row + 1][$column])
            || isset($this->elves[$row + 1][$column - 1])
            || isset($this->elves[$row - 1][$column + 1])
            || isset($this->elves[$row - 1][$column])
            || isset($this->elves[$row - 1][$column - 1])
        ) return false;
        return true;
    }

    private function considerMove($row, $column)
    {
        foreach ($this->directions as $d) {
            switch ($d) {
                case 'N':
                    if (
                        !isset($this->elves[$row - 1][$column - 1])
                        && !isset($this->elves[$row - 1][$column])
                        && !isset($this->elves[$row - 1][$column + 1])
                    ) {
                        return $row - 1 . '_' . $column;
                    }
                    break;
                case 'S':
                    if (
                        !isset($this->elves[$row + 1][$column - 1])
                        && !isset($this->elves[$row + 1][$column])
                        && !isset($this->elves[$row + 1][$column + 1])
                    ) {
                        return $row + 1 . '_' . $column;
                    }
                    break;
                case 'W':
                    if (
                        !isset($this->elves[$row - 1][$column - 1])
                        && !isset($this->elves[$row][$column - 1])
                        && !isset($this->elves[$row + 1][$column - 1])
                    ) {
                        return $row . '_' . $column - 1;
                    }
                    break;
                case 'E':
                    if (
                        !isset($this->elves[$row - 1][$column + 1])
                        && !isset($this->elves[$row][$column + 1])
                        && !isset($this->elves[$row + 1][$column + 1])
                    ) {
                        return $row . '_' . $column + 1;
                    }
                    break;
            }
        }
        return false;
    }

    private function displayElves()
    {
        for ($i = $this->minRow; $i <= $this->maxRow; $i++) {
            echo "\n";
            for ($j = $this->minColumn; $j <= $this->maxColumn; $j++) {
                echo isset($this->elves[$i][$j]) ? '#' : '.';
            }
        }
        echo "\n";
    }
}
