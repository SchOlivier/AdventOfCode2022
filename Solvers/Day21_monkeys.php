<?php

namespace App\Solvers;

class Day21_monkeys
{
    const FILE = 'assets/21-monkeys';
    private array $monkeys;

    public function partOne()
    {
        $this->readInput();
        echo $this->getMonkeyValue("root") . "\n";
    }

    private function readInput()
    {
        $this->monkeys = [];
        $file = fopen(self::FILE, 'r');

        while (($monkey = fgets($file)) !== false) {
            if (preg_match('/([a-z]{4}): (\d+)/', $monkey, $matches)) {
                $name = $matches[1];
                $value = $matches[2];
                $this->monkeys[$name] = (int)$value;
            } else {
                preg_match('/([a-z]{4}): ([a-z]{4}) (.) ([a-z]{4})/', $monkey, $matches);
                $name = $matches[1];
                $operand1 = $matches[2];
                $sign = $matches[3];
                $operand2 = $matches[4];
                $this->monkeys[$name] = [$operand1, $sign, $operand2];
            }
        }
    }

    public function partTwo()
    {
        $this->readInput();
        $i = 0;
        $monkey1 = $this->monkeys['root'][0];
        $monkey2 = $this->monkeys['root'][2];
        while($this->getMonkeyValue($monkey1, $i) !== $this->getMonkeyValue($monkey2, $i)){
            $i++;
        }
        echo $i . "\n";
    }

    private function getMonkeyValue(string $name, int|false $humnValue = false)
    {
        if ($name == 'humn' && $humnValue !== false) return $humnValue;
        if (!is_array($this->monkeys[$name])) return $this->monkeys[$name];

        $monkey = $this->monkeys[$name];

        $operand1 = $this->getMonkeyValue($monkey[0], $humnValue);
        $operand2 = $this->getMonkeyValue($monkey[2], $humnValue);
        switch ($monkey[1]) {
            case '+':
                $value = $operand1 + $operand2;
                break;
            case '*':
                $value = $operand1 * $operand2;
                break;
            case '/':
                $value = $operand1 / $operand2;
                break;
            case '-':
                $value = $operand1 - $operand2;
                break;
            default:
                echo "woops, missing sign " . $monkey[1] . "\n";
                die();
        }
        return $value;
    }
}

