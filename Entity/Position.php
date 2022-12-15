<?php

namespace App\Entity;

class Position
{
    public function __construct(
        public int $X,
        public int $Y,
        public mixed $value = null
    ) {
    }

    public function __toString()
    {
        $string = $this->X . "_" . $this->Y;
        if(!is_null($this->value)){
            $string .= " : " . $this->value;
        }
        return $string;
    }
}