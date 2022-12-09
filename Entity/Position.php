<?php

namespace App\Entity;

class Position
{
    public function __construct(
        public int $X,
        public int $Y,
    ) {
    }

    public function __toString()
    {
        return $this->X . "_" . $this->Y;
    }
}
