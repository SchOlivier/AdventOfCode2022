<?php

namespace App\Solvers;

use App\Entity\Node;
use App\Entity\Position;

class Day12_Graph
{

    const DATA_PATH = __DIR__ . '/../assets/12-ShortestPath.txt';

    private array $map;
    private array $nodes;
    private array $lowestPoints;
    private Position $startPosition;
    private Position $endPosition;

    public function findShortestPathWithFixedStartPoint(): int
    {
        $this->calculateDistances();
        return $this->getNode($this->startPosition)->distance;
    }

    public function findShortestPathFromLowestElevation(): int
    {
        $this->calculateDistances();

        $shortestDistance = PHP_INT_MAX;
        foreach ($this->lowestPoints as $p) {
            $shortestDistance = min($shortestDistance, $this->getNode($p)->distance);
        }

        return $shortestDistance;
    }

    private function calculateDistances(): int
    {
        $this->mapGraph();

        $start = $this->getNode($this->endPosition);
        $start->visited = true;
        $start->distance = 0;
        $queue = [$start];

        while (!empty($queue)) {
            $currentNode = array_shift($queue);
            foreach ($currentNode->adjacent as $adjacentNode) {
                if (!$adjacentNode->visited) {
                    $adjacentNode->visited = true;
                    $adjacentNode->distance = $currentNode->distance + 1;
                    $queue[] = $adjacentNode;
                }
            }
        }
        return 0;
    }

    private function mapGraph(): void
    {
        $this->map = [];
        $this->nodes = [];
        $this->lowestPoints = [];

        $handle = fopen(self::DATA_PATH, 'r');

        $i = 0;
        while (($line = fgets($handle)) !== false) {
            foreach (str_split(trim($line)) as $j => $char) {
                $position = new Position($i, $j);
                if ($char == 'S') {
                    $this->startPosition = $position;
                    $char = 'a';
                }
                if ($char == 'E') {
                    $this->endPosition = $position;
                    $char = 'z';
                }
                if ($char == 'a') {
                    $this->lowestPoints[] = new Position($i, $j);
                }
                $this->map[$i][$j] = ord($char);
                $this->nodes[$i][$j] = new Node(position: $position);
            }
            $i++;
        }

        foreach ($this->map as $i => $line) {
            foreach ($line as $j => $height) {
                $position = new Position($i, $j);
                $node = $this->getNode($position);
                $node->adjacent = $this->getAdjacentNodes($position, $height);
            }
        }
    }

    private function getNode(Position $p): Node
    {
        return $this->nodes[$p->X][$p->Y];
    }

    private function getAdjacentNodes(Position $p, int $height): array
    {
        $adjacent = [];

        //left
        $x = $p->X - 1;
        $y = $p->Y;
        if (isset($this->map[$x][$y]) && $height <= $this->map[$x][$y] + 1) $adjacent[] = $this->nodes[$x][$y];
        //right
        $x = $p->X + 1;
        $y = $p->Y;
        if (isset($this->map[$x][$y]) && $height <= $this->map[$x][$y] + 1) $adjacent[] = $this->nodes[$x][$y];
        //up
        $x = $p->X;
        $y = $p->Y - 1;
        if (isset($this->map[$x][$y]) && $height <= $this->map[$x][$y] + 1) $adjacent[] = $this->nodes[$x][$y];
        //down
        $x = $p->X;
        $y = $p->Y + 1;
        if (isset($this->map[$x][$y]) && $height <= $this->map[$x][$y] + 1) $adjacent[] = $this->nodes[$x][$y];

        return $adjacent;
    }
}
