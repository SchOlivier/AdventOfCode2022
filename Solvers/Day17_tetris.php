<?php

namespace App\Solvers;

use App\Entity\Position;
use App\Entity\Tetris\AbstractShape;
use App\Entity\Tetris\HLine;
use App\Entity\Tetris\MirroredL;
use App\Entity\Tetris\Plus;
use App\Entity\Tetris\Square;
use App\Entity\Tetris\VLine;

class Day17_tetris
{
    const freshLine = [-1 => '|', 7 => '|'];
    const DATA_PATH = __DIR__ . '/../assets/17-tetris';

    const RIGHT = '>';
    const LEFT = '<';

    private array $rocks;
    private int $height;
    private int $buffer = 0;
    private array $wind;
    private int $windMod;
    private int $windCounter;
    private bool $firstArraySlice = true;

    public function partOne()
    {
        // init
        $limit = 1e5;
        $this->rocks = [];
        $this->height = 0;
        $this->rocks[-1][-1] = $this->rocks[-1][7] = '+';
        for ($x = 0; $x < 7; $x++) {
            $this->rocks[-1][$x] = '-';
        }
        $this->wind = str_split(file_get_contents(self::DATA_PATH));
        $this->windMod = count($this->wind);
        $this->windCounter = 0;

        // Starting Tetris
        $count = 0;
        while (true) {
            $shape = new HLine(new Position(2, $this->height + 3));
            $this->dropShape(shape: $shape);
            $count++;
            if ($count == $limit) break;

            $shape = new Plus(new Position(2, $this->height + 3));
            $this->dropShape(shape: $shape);
            $count++;
            if ($count == $limit) break;

            $shape = new MirroredL(new Position(2, $this->height + 3));
            $this->dropShape(shape: $shape);
            $count++;
            if ($count == $limit) break;

            $shape = new VLine(new Position(2, $this->height + 3));
            $this->dropShape(shape: $shape);
            $count++;
            if ($count == $limit) break;

            $shape = new Square(new Position(2, $this->height + 3));
            $this->dropShape(shape: $shape);
            $count++;
            if ($count == $limit) break;

            if ($count % 10000 == 0) {
                echo "iteration $count - current height : " . $this->height + $this->buffer . "\n";
            }
        }

        // $this->displayRocks();

        echo "\n---------------------\n";
        echo   "- Hauteur max : " . $this->height + $this->buffer . " -\n";
        echo "---------------------\n";
    }

    private function dropShape(AbstractShape $shape): void
    {
        for ($i = 0; $i < 3 + $shape->height; $i++) {
            $this->rocks[$this->height + $i] = self::freshLine;
        }
        do {
            if ($this->wind[$this->windCounter % $this->windMod] == '>') {
                $this->moveRight($shape);
            } else {
                $this->moveLeft($shape);
            }
            $this->windCounter++;
        } while ($this->moveDown($shape));
        $this->settleShape($shape);

        $this->reduceArray($shape);
    }


    private function reduceArray(AbstractShape $shape): void
    {
        //truncate rocks if full line (tetris style) : what's below can't impact the total height
        for ($y = $shape->position->Y + $shape->height - 1; $y >= $shape->position->Y; $y--) {
            $fullLine = true;
            for ($x = 0; $x < 7; $x++) {
                if (!isset($this->rocks[$y][$x])) {
                    $fullLine = false;
                    break;
                }
            }
            if ($fullLine) {
                // $this->displayRocks();
                $this->buffer += $y;
                $this->height = $this->height - $y;

                if ($this->firstArraySlice) {
                    $this->firstArraySlice = false;
                    $this->rocks = array_slice($this->rocks, $y + 1);
                } else {
                    $this->rocks = array_slice($this->rocks, $y);
                }

                break;
            }
        }
    }

    private function moveRight(AbstractShape $shape): void
    {
        // echo "le vent soufle à droite\n";
        if ($this->isPositionsEmpty($shape->getPositionsToCheck('>'))) {
            // echo "Je peux bouger à droite\n";
            $shape->moveRight();
        } else {
            // echo "Je ne peux pas bouger à droite.\n";
        }
    }

    private function moveLeft(AbstractShape $shape): void
    {
        // echo "le vent soufle à gauche\n";
        if ($this->isPositionsEmpty($shape->getPositionsToCheck('<'))) {
            // echo "Je peux bouger à gauche\n";
            $shape->moveLeft();
        } else {
            // echo "Je ne peux pas bouger à gauche.\n";
        }
    }

    private function moveDown(AbstractShape $shape): bool
    {
        // echo "Je veux descendre\n";
        if ($this->isPositionsEmpty($shape->getPositionsToCheck('v'))) {
            // echo "Je peux descendre\n";
            $shape->moveDown();
            return true;
        }
        return false;
    }

    private function displayRocks(?AbstractShape $shape = null): void
    {
        $rocks = $this->rocks;
        if ($shape) {
            $shapePositions = $shape->getPositions();
            foreach ($shapePositions as $position) {
                $rocks[$position->Y][$position->X] = '@';
            }
        }

        echo "\n";
        for ($y = count($rocks) - 2; $y >= -1; $y--) {
            echo $y >= 0 ? str_pad($y, 4, " ", STR_PAD_LEFT) : '    ';
            for ($x = -1; $x < 8; $x++) {
                echo $rocks[$y][$x] ?? '.';
            }
            echo "\n";
        }
        echo '     ';
        for ($x = 0; $x < 8; $x = $x + 2) {
            echo $x . ' ';
        }
        echo "\n";
    }

    private function settleShape(AbstractShape $shape): void
    {
        $shapePositions = $shape->getPositions();
        foreach ($shapePositions as $p) {
            $this->height = max($this->height, $p->Y + 1);
            $this->rocks[$p->Y][$p->X] = '#';
        }
    }

    private function isPositionsEmpty(array $positions): bool
    {
        foreach ($positions as $p) {
            // echo "Je vérifie la position $p\n";
            if (isset($this->rocks[$p->Y][$p->X])) return false;
        }
        return true;
    }
}
