<?php

namespace App\Solvers;

class Day10_CRT
{
    const DATA_PATH = __DIR__ . '/../assets/10-signalStrength.txt';
    public function getSignalStrength(array $cycles): int
    {
        $signalStrength = 0;
        $handle = fopen(self::DATA_PATH, 'r');

        $register = 1;
        $nbCycle = 1;

        while (($line = fgets($handle)) !== false) {
            if (in_array($nbCycle, $cycles)) $signalStrength += $nbCycle * $register;
            $operation = substr($line, 0, 4);
            $nbCycle++;
            if ($operation == 'addx') {
                if (in_array($nbCycle, $cycles)) $signalStrength += $nbCycle * $register;
                $value = (int)substr(trim($line), 5);
                $register += $value;
                $nbCycle++;
            }
        }

        return $signalStrength;
    }

    public function displayMessage(): void
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $spritePosition = 1;
        $cycle = 1;

        while (($line = fgets($handle)) !== false) {
            $this->displayPixel($cycle, $spritePosition);
            $operation = substr($line, 0, 4);
            $cycle++;
            if ($operation == 'addx') {
                $this->displayPixel($cycle, $spritePosition);
                $value = (int)substr(trim($line), 5);
                $spritePosition += $value;
                $cycle++;
            }
        }
    }

    private function displayPixel($cycle, $spritePosition): void
    {
        $cycle--;
        $x = $cycle % 40;
        if ($x == 0) echo "\n";
        if ($spritePosition - 1 <= $x && $x <= $spritePosition + 1) {
            echo "0";
        } else {
            echo " ";
        }
    }
}
