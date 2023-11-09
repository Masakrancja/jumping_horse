<?php
declare(strict_types=1);
namespace App;

class Horse
{
    public array $table = []; //Canvas
    public array $moves = []; //Path of horse moves

    private array $p = []; // current position
    private array $directions = [[-2, -1], [-1, -2], [1, -2], [2, -1], [2, 1], [1, 2], [-1, 2], [-2, 1]];
    private int $X;
    private int $Y;

    public static function dump($any): void
    {
        if (php_sapi_name() === 'cli') {
            print_r($any);
        } else {
            echo "<pre>";
            print_r($any);
            echo "<pre>";
        }
    }

    public function __construct(int $X, int $Y, int $x, int $y) {
        $this->X = $X;
        $this->Y = $Y;
        $this->table = $this->createTable();
        $this->p[0] = $x;
        $this->p[1] = $y;
        $this->addToMoves();
        $this->addToTable();
    }

    public function drawTable(int $spaces=4): void
    {
        if ($this->isCli()) {
            echo "\n┌";
            for ($i=0; $i<$this->X; $i++) {
                echo str_repeat('─', ($spaces + 1));
                if ($i < ($this->X - 1)) {
                    echo '┬';
                }
            }
            echo "┐\n";
            for ($j=0; $j<$this->Y; $j++) {
                for ($i=0; $i<$this->X; $i++) {
                    printf("│%+" . $spaces . "s", (string) $this->table[$i][$j]);
                    echo ' ';
                    if ($i == ($this->X - 1)) {
                        echo '│';
                    }
                }
                echo "\n";
                if ($j < ($this->Y - 1)) {
                    $leftSign = '├';
                    $centerSign = '┼';
                    $rightSign = '┤';
                } else {
                    $leftSign = '└';
                    $centerSign = '┴';
                    $rightSign = '┘';
                }
                echo $leftSign;
                for ($i=0; $i<$this->X; $i++) {
                    echo str_repeat('─', ($spaces + 1));
                    if ($i < ($this->X - 1)) {
                        echo $centerSign;
                    }
                }
                echo $rightSign . "\n";
            }
        } else {
            echo 'draw table';

            echo "│ ┤ ┐ └ ┴ ┬ ├ ─ ┼ ┘ ┌";
        }
    }

    public function move(): bool
    {
        $possibleMoves = $this->removeUsedPositions(
            $this->getPossibleMoves()
        );
        if (!empty($possibleMoves)) {
            $p = $this->getNewPosition($possibleMoves);
            if ($p) {
                $this->p = $p;
                $this->addToMoves();
                $this->addToTable();
                return true;
            }
        }
        return false;
    }

    private function isCli(): bool
    {
        return (php_sapi_name() === 'cli') ? true : false;
    }

    private function createTable(): array
    {
        $result = [];
        for ($i=0; $i<$this->X; $i++) {
            for ($j=0; $j<$this->Y; $j++) {
                $result[$i][$j] = null;
            }
        }
        return $result;
    }

    private function addToMoves(): void
    {
        array_push($this->moves, $this->p);
    }

    private function addToTable(): void
    {
        $this->table[$this->p[0]][$this->p[1]] = count($this->moves);
    }

    private function getPossibleMoves(): array
    {
        $result = [];
        foreach ($this->directions as $direction) {
            $move = [];
            $move[0] = $direction[0] + $this->p[0];
            $move[1] = $direction[1] + $this->p[1];
            if (
                $move[0] >= 0 and 
                $move[1] >= 0 and 
                $move[0] < $this->X 
                and $move[1] < $this->Y
            ) {
                $result[] = $move;
            }
        }
        return $result;
    }

    private function getNewPosition(array $possibleMoves): ?array
    {
        if (!empty($possibleMoves)) {
            $c = count($possibleMoves);
            $m = microtime(true);
            $seed = (int) ((($m - (int) $m) * 1000000000) % 100000);

            echo 'seed: ' . $seed . "\n";

            srand($seed);
            return $possibleMoves[rand(0, $c - 1)];
        }
        return null;
    }

    private function removeUsedPositions(array $possibleMoves): array
    {
        $result = [];
        foreach ($possibleMoves as $move) {
            if (!in_array($move, $this->moves)) {
                $result[] = $move;
            }
        }
        return $result;
    }






}