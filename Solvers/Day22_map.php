<?php

namespace App\Solvers;

use App\Entity\Position;

class Day22_map
{
    const TURNS_MAPPING = [
        'R' => [
            '^' => '>',
            '>' => 'v',
            'v' => '<',
            '<' => '^'
        ],
        'L' => [
            '^' => '<',
            '>' => '^',
            'v' => '>',
            '<' => 'v'
        ]
    ];

    const DIRECTION_OFFSET = [
        '^' => [0, -1],
        '>' => [1, 0],
        'v' => [0, 1],
        '<' => [-1, 0]
    ];

    const DIRECTION_VALUE = [
        '^' => 3,
        '>' => 0,
        'v' => 1,
        '<' => 2
    ];

    private array $map;
    private array $moves;
    private array $turns;
    private Position $position;
    private array $path;
    private array $borders;
    private int $height;
    private int $width;

    public function partOne()
    {
        $this->readInput();

        $startColumn = $this->borders['>'][1];

        $this->position = new Position($startColumn, 1, '>');
        $this->path[] = clone $this->position;
        // $this->displayMap();

        while (!empty($this->moves) || !empty($this->turns)) {
            echo "\n";
            $this->move(array_shift($this->moves));
            $this->turn(array_shift($this->turns));
            // $this->displayMap(toFile:true);
            // $a = readline("continue ?");
            // if($a == 'n') die;
        }

        $this->displayMap(toFile:true);
        echo "Final position : " . $this->position . "\n";
        echo "Password : " . $this->position->Y * 1000 + $this->position->X * 4  + self::DIRECTION_VALUE[$this->position->value] . "\n";
    }

    private function readInput()
    {
        $file = fopen('assets/22-map', 'r');
        $j = 1;

        $width = 0;
        while (($line = fgets($file)) !== false) {
            $width = max($width, strlen($line) - 1);
        }
        rewind($file);

        while (($line = fgets($file)) !== false) {
            if (trim($line) == '') break;
            $chars = str_split($line);
            for ($i = 0; $i < $width; $i++) {
                $char = isset($chars[$i]) && in_array($chars[$i], ['.', ' ', '#']) ? $chars[$i] : ' ';
                $this->map[$i + 1][$j] = $char;
                if ($char == '.' || $char == '#') {
                    if (!isset($this->borders['>'][$j])) $this->borders['>'][$j] = $i + 1;
                    $this->borders['<'][$j] = $i + 1;

                    if (!isset($this->borders['v'][$i + 1])) $this->borders['v'][$i + 1] = $j;
                    $this->borders['^'][$i + 1] = $j;
                }
            }

            $j++;
        }

        $this->width = $width;
        $this->height = $j - 1;

        $line =  trim(fgets($file));
        preg_match_all('/(\d+)/', $line, $matches);
        $this->moves = $matches[1];

        preg_match_all('/(\D+)/', $line, $matches);
        $this->turns = $matches[1];
    }

    private function move(?int $move)
    {
        echo "move : $move\n";
        if (is_null($move)) return;
        $direction = self::DIRECTION_OFFSET[$this->position->value];
        while ($move > 0) {
            $nextPosition = $this->position->getRelativePosition($direction[0], $direction[1]);

            if (
                !isset($this->map[$nextPosition->X][$nextPosition->Y]) ||
                $this->map[$nextPosition->X][$nextPosition->Y] == ' '
            ) {
                switch ($this->position->value) {
                    case '>':
                        $nextPosition->X = $this->borders['>'][$this->position->Y];
                        break;
                    case '<':
                        $nextPosition->X = $this->borders['<'][$this->position->Y];
                        break;
                    case '^':
                        $nextPosition->Y = $this->borders['^'][$this->position->X];
                        break;
                    case 'v':
                        $nextPosition->Y = $this->borders['v'][$this->position->X];
                        break;
                }
            }
            if($this->map[$nextPosition->X][$nextPosition->Y] == '#') {
                return;
            }

            $this->position = $nextPosition;
            $this->path[] = clone $this->position;
            $move--;
        }
    }

    private function turn(?string $turn)
    {
        echo "direction : $turn\n";
        if (is_null($turn)) return;
        $this->position->value = self::TURNS_MAPPING[$turn][$this->position->value];
        $this->path[] = clone $this->position;
    }

    private function displayMap(bool $withPath = true, bool $toFile = false)
    {
        $display = '';

        $map = $this->map;
        if ($withPath) {
            foreach ($this->path as $p) {
                $map[$p->X][$p->Y] = $p->value;
            }
        }
        for ($j = 1; $j <= $this->height; $j++) {
            $display .= "\n";
            for ($i = 1; $i <= $this->width; $i++) {
                $display .= $map[$i][$j];
            }
        }
        $display .= "\n";
        if($toFile){
            file_put_contents('assets/22-finalMap.txt', $display);
        } else {
            echo $display;
        }
    }
}
