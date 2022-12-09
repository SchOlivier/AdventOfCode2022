<?php

namespace App\Solvers;

use App\Entity\Position;

class Day09_RopeMovement
{
    const DATA_PATH = __DIR__ . '/../assets/09-RopeMovement.txt';

    const UP = 'U';
    const DOWN = 'D';
    const LEFT = 'L';
    const RIGHT = 'R';

    private Position $tail;

    public function __construct()
    {
    }

    public function countVisitedPositions()
    {
        $head = new Position(0, 0);
        $tail = new Position(0, 0);
        $visitedPositions[$tail->__toString()] = 1;
        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
            list($direction, $nbMoves) = explode(" ", trim($line));
            while ($nbMoves > 0) {
                $nbMoves--;
                $this->moveHead($head, $direction);
                $this->updateTailPosition($head, $tail);
                $visitedPositions[$tail->__toString()] = 1;
            }
        }
        return count($visitedPositions);
    }

    public function countVisitedPositionsWith9Tails()
    {
        $knots = [new Position(0,0)]; //head
        for ($i = 1; $i < 10; $i++)
        {
            $knots[$i] = new Position(0,0);
        }
        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
            list($direction, $nbMoves) = explode(" ", trim($line));
            while ($nbMoves > 0) {
                $nbMoves--;
                $this->moveHead($knots[0], $direction);
                for($i = 0; $i < 9; $i++)
                {
                    $this->updateTailPosition($knots[$i], $knots[$i+1]);
                }
                $visitedPositions[$knots[9]->__toString()] = 1;
            }
        }
        return count($visitedPositions);
    }

    private function moveHead(Position $head, $direction)
    {
        switch ($direction) {
            case self::UP;
                $head->Y++;
                break;
            case self::DOWN:
                $head->Y--;
                break;
            case self::LEFT:
                $head->X--;
                break;
            case self::RIGHT:
                $head->X++;
                break;
        }
    }

    private function updateTailPosition(Position $head, Position $tail)
    {
        if (abs($head->X - $tail->X) > 1 && abs($head->Y - $tail->Y) > 1) {
            $tail->X = $head->X - ($head->X - $tail->X) / abs($head->X - $tail->X);
            $tail->Y = $head->Y - ($head->Y - $tail->Y) / abs($head->Y - $tail->Y);
        } elseif (abs($head->X - $tail->X) > 1) {
            $tail->X = $head->X - ($head->X - $tail->X) / abs($head->X - $tail->X);
            $tail->Y = $head->Y;
        } elseif (abs($head->Y - $tail->Y) > 1) {
            $tail->X = $head->X;
            $tail->Y = $head->Y - ($head->Y - $tail->Y) / abs($head->Y - $tail->Y);
        }
    }
}
