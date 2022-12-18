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
    const CREST_FILE = __DIR__ . '/../assets/tmp.csv';
    const DOUBLONS_FILE = __DIR__ . '/../assets/doublons.csv';

    const RIGHT = '>';
    const LEFT = '<';

    private array $rocks;
    private int $height;
    private int $buffer = 0;
    private array $wind;
    private int $windMod;
    private int $windCounter;
    private bool $firstArraySlice = true;

    public function stackRocks(int $limit)
    {
        // init
        $this->rocks = [];
        $this->height = 0;
        $this->rocks[-1][-1] = $this->rocks[-1][7] = '+';
        for ($x = 0; $x < 7; $x++) {
            $this->rocks[-1][$x] = '-';
        }
        $this->wind = str_split(file_get_contents(self::DATA_PATH));
        $this->windMod = count($this->wind);
        $this->windCounter = 0;
        // $crestFile = fopen(self::CREST_FILE, 'w');
        // $doublonsFile = fopen(self::DOUBLONS_FILE, 'w');
        // fwrite($crestFile, "counter ; current height ; buffer height ; total height ; crest[0-6]\n");
        // fwrite($doublonsFile, "counter ; current height ; buffer height ; total height ; crest[0-6]\n");
        $crestLines = [];

        echo "\n windMod : " . $this->windMod . "\n";

        $baseShapes = [
            new HLine(new Position(0, 0)),
            new Plus(new Position(0, 0)),
            new MirroredL(new Position(0, 0)),
            new VLine(new Position(0, 0)),
            new Square(new Position(0, 0))
        ];


        // Starting Tetris
        $count = 0;
        while (true) {

            for ($i = 0; $i < 5; $i++){
                if($count %  (5*$this->windMod) === 0){
                    
                    // echo "iteration $count - current height : " . $this->height + $this->buffer . "\n";

                    $crest = [];
                    $yMin = $this->height;
                    for($x = 0; $x < 7; $x++){
                        $y = $this->height;
                        while(!isset($this->rocks[$y][$x])){
                            $y--;
                        }
                        $crest[] = $y;
                        $yMin = min($yMin, $y);
                    }
                    for($j = 0; $j < 7 ; $j++){
                        $crest[$j] -= $yMin;
                    }
                    echo "Ligne de crête : " . implode('; ', $crest) . "\n";
                    // fwrite($crestFile, $count . ";" . $this->height . ";". $this->buffer . ";" . $this->height + $this->buffer .";" .implode(';', $crest) . "\n");

                    if(in_array($crest, $crestLines)){
                        echo "-------------\n";
                        echo "- Doublon ! -\n";
                        echo "-------------\n";
                        echo "Count : $count, currentheight : " . $this->height . ", buffer : " . $this->buffer . ", Total Height : " . $this->height + $this->buffer . "\n";
                        // fwrite($doublonsFile, $count . ";" . $this->height . ";". $this->buffer . ";" . $this->height + $this->buffer .";" .implode(';', $crest) . "\n");
                    }
                    $crestLines[] = $crest;
                }
                if ($count == $limit) break(2);


                $shape = $baseShapes[$i];
                $shape->position = new Position(2, $this->height + 3);
                $this->dropShape(shape: $shape);
                $count++;
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
                $this->buffer += $y;
                $this->height = $this->height - $y;

                if ($this->firstArraySlice) {
                    $this->firstArraySlice = false;
                    $this->rocks = array_slice($this->rocks, $y + 1);
                } else {
                    $this->rocks = array_slice($this->rocks, $y);
                }

                // $emptyFirstLine = true;
                // for($i = 0; $i < 7; $i++){
                //     if(isset($this->rocks[1][$x])){
                //         $emptyFirstLine = false;
                //         break;
                //     }
                // }

                // if($emptyFirstLine){
                //     echo "j'ai reset le board !! \n";
                //     $this->displayRocks();
                //     die();
                // }
                
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
