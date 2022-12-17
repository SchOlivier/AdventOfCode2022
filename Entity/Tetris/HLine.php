<?php

namespace App\Entity\Tetris;

use App\Entity\Position;

class HLine extends AbstractShape
{
    // Horizontal line. Shape : @###
    // '@' is the point of reference to determine the position


    public function __construct(public Position $position)
    {
        $this->height = 1;
        $this->width = 4;
    }

    public function getPositionsToCheck(string $direction): array
    {
        if ($direction == self::LEFT) return [$this->position->getRelativePosition(-1, 0)];
        if ($direction == self::RIGHT) return [$this->position->getRelativePosition($this->width, 0)];

        //down
        $positions = [];
        for ($i = 0; $i < 4; $i++){
            $positions[] = $this->position->getRelativePosition($i, -1);
        }
        return $positions;
    }

    public function getPositions(): array
    {
        $positions = [];
        for ($i = 0; $i < 4; $i++){
            $positions[] = $this->position->getRelativePosition($i, 0);
        }
        return $positions;
    }

}
