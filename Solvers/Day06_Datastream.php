<?php

namespace App\Solvers;

class Day06_Datastream
{

    const DATA_PATH = __DIR__ . '/../assets/06-datastream.txt';

    public function getIndexOfStartOfPacket()
    {
        return $this->getIndexAfterNDistinctChars(4);
    }

    public function getIndexOfStartOfMessage()
    {
        return $this->getIndexAfterNDistinctChars(14);
    }

    private function getIndexAfterNDistinctChars(int $n): int
    {
        $datastream = file_get_contents(self::DATA_PATH);

        $buffer = [];
        for ($i = 0; $i < $n; $i++) {
            $buffer[] = $datastream[$i];
        }
        $distinctChars = array_unique($buffer);
        
        $i = $n;
        while (sizeof($distinctChars) < $n) {
            $buffer[] = $datastream[$i];
            array_shift($buffer);
            $distinctChars = array_unique($buffer);
            $i++;
        }
        return $i;
    }
}
