<?php

namespace App\Solvers;

class Day08_TreeHouse
{
    const DATA_PATH = __DIR__ . '/../assets/08-Forest.txt';
    const GRID_SIZE = 99;

    private array $trees;
    private array $treesVisibility = [];

    public function __construct()
    {
        $this->trees = $this->readInput();
    }

    public function countVisibleTrees(): int
    {
        $this->initTreesVisibility();
        $this->getHorizontalVisibility();
        $this->getVerticalVisibility();

        $visibleTrees = 0;
        foreach ($this->treesVisibility as $row) {
            $visibleTrees += array_sum($row);
        }
        return $visibleTrees;
    }

    public function getHighestScenicScore(): int
    {
        $score = 0;
        for ($i = 1; $i < self::GRID_SIZE - 1; $i++) {
            for ($j = 1; $j < self::GRID_SIZE - 1; $j++) {
                $score = max($score, $this->getTreeScenicScore($i, $j));
            }
        }
        return $score;
    }

    private function readInput(): array
    {
        $trees = [];
        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
            $row = str_split(trim($line), 1);
            $row = array_map(function ($tree) {
                return (int)$tree;
            }, $row);
            $trees[] = $row;
        }
        return $trees;
    }

    private function initTreesVisibility()
    {
        $this->treesVisibility = array_fill(0, self::GRID_SIZE, array_fill(0, self::GRID_SIZE, 0));
    }

    private function getHorizontalVisibility()
    {
        for ($i = 0; $i < self::GRID_SIZE; $i++) {
            $leftHighestTree = -1;
            $rightHighestTree = -1;
            for ($j = 0; $j < self::GRID_SIZE; $j++) {
                if ($this->trees[$i][$j] > $leftHighestTree) {
                    $leftHighestTree = $this->trees[$i][$j];
                    $this->treesVisibility[$i][$j] = 1;
                }
                if ($this->trees[$i][self::GRID_SIZE - $j - 1] > $rightHighestTree) {
                    $rightHighestTree = $this->trees[$i][self::GRID_SIZE - $j - 1];
                    $this->treesVisibility[$i][self::GRID_SIZE - $j - 1] = 1;
                }
            }
        }
    }

    private function getVerticalVisibility()
    {
        for ($i = 0; $i < self::GRID_SIZE; $i++) {
            $topHighestTree = -1;
            $bottomHighestTree = -1;
            for ($j = 0; $j < self::GRID_SIZE; $j++) {
                if ($this->trees[$j][$i] > $topHighestTree) {
                    $topHighestTree = $this->trees[$j][$i];
                    $this->treesVisibility[$j][$i] = 1;
                }
                if ($this->trees[self::GRID_SIZE - $j - 1][$i] > $bottomHighestTree) {
                    $bottomHighestTree = $this->trees[self::GRID_SIZE - $j - 1][$i];
                    $this->treesVisibility[self::GRID_SIZE - $j - 1][$i] = 1;
                }
            }
        }
    }

    private function getTreeScenicScore(int $i0, int $j0)
    {

        $treeSize = $this->trees[$i0][$j0];
        //top
        $topCount = 0;
        $i = $i0 -1;
        while ($i >= 0) {
            $topCount++;
            if($this->trees[$i][$j0] >= $treeSize) break;
            $i--;
        }

        //bottom
        $bottomCount = 0;
        $i = $i0 +1;
        while ($i < self::GRID_SIZE) {
            $bottomCount++;
            if( $this->trees[$i][$j0] >= $treeSize) break;
            $i++;
        }

        //left
        $leftCount = 0;
        $j = $j0 -1;
        while ($j >= 0 ) {
            $leftCount++;
            if($this->trees[$i0][$j] >= $treeSize) break;
            $j--;
        }

        //right
        $rightCount = 0;
        $j = $j0 +1;
        while ($j < self::GRID_SIZE) {
            $rightCount++;
            if ( $this->trees[$i0][$j] >= $treeSize) break;
            $j++;
        }

        return $topCount * $bottomCount * $rightCount * $leftCount;
    }
}
