<?php
declare(strict_types=1);
namespace App;

class Horse
{
    public array $table = []; //Canvas
    public array $moves = []; //Path of horse moves

    private array $p = []; // current position
    private array $directions = [[-2, -1], [-1, -2], [1, -2], [2, -1], [2, 1], [1, 2], [-1, 2], [-2, 1]];
    public array $randomNumbers = [];
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
        $this->addPointToTable();
        $this->initRandomNumbers();
    }

    public function setStartPoint(int $x, int $y): void
    {
        $this->p[0] = $x;
        $this->p[1] = $y;
        $this->clearTable();
        $this->clearMoves();
        $this->addToMoves();
        $this->addPointToTable();
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
            ?>
            <?php for ($j=0; $j<$this->Y; $j++): ?>
                <div style="
                    display: flex;
                    background-color: DodgerBlue;
                    flex-flow: row nowrap;
                    justify-content: center;
                ">
                    <?php for ($i=0; $i<$this->X; $i++): ?>
                        <div style="
                            background-color: #f1f1f1;
                            margin: 5px;
                            padding: 5px;
                            font-size: 24px;  
                            flex-basis: 50px;
                            text-align: center;  
                        ">
                            <?php echo $this->table[$i][$j]; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
            <?php            
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
                $this->addPointToTable();
                return true;
            }
        }
        return false;
    }

    public function fillTable(array $moves): void
    {
        foreach ($moves as $key => $move) {
            $this->table[$move[0]][$move[1]] = $key + 1;
        }
    }

    public function isCli(): bool
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

    private function clearTable(): void
    {
        for ($i=0; $i<$this->X; $i++) {
            for ($j=0; $j<$this->Y; $j++) {
                $this->table[$i][$j] = null;
            }
        }
    }

    private function addToMoves(): void
    {
        array_push($this->moves, $this->p);
    }

    private function clearMoves(): void
    {
        $this->moves = [];
    }

    private function addPointToTable(): void
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

            if (!empty($this->randomNumbers)) {
                return $possibleMoves[
                    $this->getRandomNumberExtra($c)
                ];
            } else {
                return $possibleMoves[
                    $this->getRandomNumber($c)
                ];
            }
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

    private function getRandomNumber(int $c): int
    {
        return rand(0, $c - 1);
    }

    private function getRandomNumberExtra(int $c): int
    {
        return $this->randomGenerator() % $c;
    }

    private function initRandomNumbers(int $min=10000000, int $max=100000000, int $count=1000000): void
    {
        for ($i=0; $i<$count; $i++) {
            $this->randomNumbers[] = rand($min, $max);
        }
    }

    // private function initRandomNumbers(string $file='rand.txt'): void
    // {
    //     if (file_exists($file) !== false) {
    //         if (($f = fopen($file, 'r')) !== false) {
    //             while ($line = fgets($f)) {
    //                 $this->randomNumbers[] = (int) $line; 
    //             }
    //             shuffle($this->randomNumbers);
    //         }
    //     }
    // }

    private function randomGenerator(): ?int
    {
        if (!empty($this->randomNumbers)) {
            return array_pop($this->randomNumbers);
        }
        return null;
    }
}