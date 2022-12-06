<?php

namespace App\Entity;

class RearrangementInstruction
{
    public function __construct(
        public int $numberOfMoves,
        public int $from,
        public int $to
    ) {
    }
}
