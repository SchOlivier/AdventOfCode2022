<?php

namespace App\Entity\Tetris;

use App\Entity\Position;

class Square extends AbstractShape
{
    // Square. Shape :
    // ##
    // @#

    // '@' is the point of reference to determine the position


    public function __construct(public Position $position)
    {
        $this->height = 2;
        $this->width = 2;
    }

    public function getPositionsToCheck(string $direction): array
    {
        if ($direction == self::LEFT) {
            return [
                $this->position->getRelativePosition(-1, 0),
                $this->position->getRelativePosition(-1, +1),
            ];
        }
        if ($direction == self::RIGHT) {
            return [
                $this->position->getRelativePosition(+2, 0),
                $this->position->getRelativePosition(+2, +1),
            ];
        }

        //down
        return [
            $this->position->getRelativePosition(0, -1),
            $this->position->getRelativePosition(+1, -1)
        ];
    }

    public function getPositions(): array
    {
        return [
            $this->position,
            $this->position->getRelativePosition(+0, +1),
            $this->position->getRelativePosition(+1, +0),
            $this->position->getRelativePosition(+1, +1)
        ];
    }
}
