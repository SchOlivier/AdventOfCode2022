<?php

namespace App\Entity\Tetris;

use App\Entity\Position;

class VLine extends AbstractShape
{
    // Vertical line. Shape
    // #
    // #
    // #
    // @

    // '@' is the point of reference to determine the position


    public function __construct(public Position $position)
    {
        $this->height = 4;
        $this->width = 1;
    }

    public function getPositionsToCheck(string $direction): array
    {
        if ($direction == self::LEFT) {
            return [
                $this->position->getRelativePosition(-1, 0),
                $this->position->getRelativePosition(-1, +1),
                $this->position->getRelativePosition(-1, +2),
                $this->position->getRelativePosition(-1, +3),
            ];
        }
        if ($direction == self::RIGHT) {
            return [
                $this->position->getRelativePosition(+1, 0),
                $this->position->getRelativePosition(+1, +1),
                $this->position->getRelativePosition(+1, +2),
                $this->position->getRelativePosition(+1, +3),
            ];
        }

        //down
        return [
            $this->position->getRelativePosition(0, -1)
        ];
    }

    public function getPositions(): array
    {
        $positions = [];
        for ($i = 0; $i < 4; $i++) {
            $positions[] = $this->position->getRelativePosition(0, $i);
        }
        return $positions;
    }
}
