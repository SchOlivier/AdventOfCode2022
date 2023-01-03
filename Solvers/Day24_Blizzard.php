<?php

namespace App\Solvers;

use App\Entity\Position;

class Day24_Blizzard
{
    private array $map;
    private array $blizzard;
    private int $height;
    private int $width;
    private Position $expedition;

    public function partOne(): int
    {
        $this->readInput();
        $this->expedition = new Position(0, 1, 'E');
        $this->displayMap(false);
        while (true) {
            $this->tick();
            $this->displayMap();
            $a = readline('continue ?');
            if ($a == 'n') die();
        }

        return 0;
    }

    private function readInput()
    {
        $file = fopen('assets/24-blizzard', 'r');
        $i = 0;
        while (($line = fgets($file)) !== false) {
            $this->width = strlen(trim($line));
            $j = 0;
            foreach (str_split(trim($line)) as $char) {
                if (in_array($char, ['>', '<', 'v', '^'])) {
                    $this->map[$i][$j][] = $char;
                    $this->blizzard[] = new Position($i, $j, $char);
                }
                $j++;
            }
            $i++;
        }
        $this->height = $i;
    }

    private function displayMap(bool $listBlizzards = false)
    {
        echo "\n";
        echo str_pad("#.", $this->width, "#");
        for ($i = 1; $i < $this->height - 1; $i++) {
            echo "\n";
            for ($j = 0; $j < $this->width; $j++) {
                if ($j == 0 || $j == $this->width - 1) {
                    echo "#";
                } elseif ($this->expedition->X == $i && $this->expedition->Y == $j) {
                    echo "E";
                } elseif (isset($this->map[$i][$j])) {
                    echo count($this->map[$i][$j]) == 1 ? $this->map[$i][$j][0] : count($this->map[$i][$j]);
                } else {
                    echo '.';
                }
            }
        }
        echo "\n" . str_repeat("#", $this->width - 2) . ".#\n\n";

        if ($listBlizzards) {
            echo "Blizzard positions : \n";
            foreach ($this->blizzard as $b) {
                echo $b . "\n";
            }
            echo "\n";
        }
    }

    private function tick(): void
    {
        $this->map = [];
        foreach ($this->blizzard as $b) {
            $this->moveBlizzard($b);
            $this->map[$b->X][$b->Y][] = $b->value;
        }
    }

    private function moveBlizzard(Position $b): void
    {
        switch ($b->value) {
            case '>':
                $b->moveBy(0, 1);
                break;
            case '<':
                $b->moveBy(0, -1);
                break;
            case '^':
                $b->moveBy(-1, 0);
                break;
            case 'v':
                $b->moveBy(1, 0);
                break;
        }
        if ($b->X == 0) $b->X = $this->height - 2;
        if ($b->X == $this->height - 1) $b->X = 1;
        if ($b->Y == 0) $b->Y = $this->width - 2;
        if ($b->Y == $this->width - 1) $b->Y = 1;
    }
}
