<?php

namespace App\Entity\Tetris;

use App\Entity\Position;

class MirroredL extends AbstractShape
{
    // Mirrored L. Shape : 
    //   #
    //   #
    // @##

    // '@' is the point of reference to determine the position.


    public function __construct(public Position $position)
    {
        $this->height = 3;
        $this->width = 3;
    }

    public function getPositionsToCheck(string $direction): array
    {
        if ($direction == self::LEFT) {
            return [
                $this->position->getRelativePosition(-1, 0),
                $this->position->getRelativePosition(+1, +1),
                $this->position->getRelativePosition(+1, +2),
            ];
        }
        if ($direction == self::RIGHT) {
            return [
                $this->position->getRelativePosition(+3, 0),
                $this->position->getRelativePosition(+3, +1),
                $this->position->getRelativePosition(+3, +2),
            ];
        }

        //down
        return [
            $this->position->getRelativePosition(0, -1),
            $this->position->getRelativePosition(+1, -1),
            $this->position->getRelativePosition(+2, -1),
        ];
    }

    public function getPositions(): array
    {
        return [
            $this->position,
            $this->position->getRelativePosition(+1, 0),
            $this->position->getRelativePosition(+2, 0),
            $this->position->getRelativePosition(+2, 1),
            $this->position->getRelativePosition(+2, 2),
        ];
    }
}
