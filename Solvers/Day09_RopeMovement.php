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

    public function countVisitedPositions($nbKnots)
    {
        for ($i = 0; $i < $nbKnots; $i++)
        {
            $knots[$i] = new Position(0,0);
        }
        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
            list($direction, $nbMoves) = explode(" ", trim($line));
            while ($nbMoves > 0) {
                $nbMoves--;
                $this->moveHead($knots[0], $direction);
                for($i = 0; $i < $nbKnots - 1 ; $i++)
                {
                    $this->updateTailPosition($knots[$i], $knots[$i+1]);
                }
                $visitedPositions[$knots[$nbKnots -1]->__toString()] = 1;
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
