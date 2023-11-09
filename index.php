<?php
declare(strict_types=1);
namespace App;
require 'vendor/autoload.php';

use App\Horse;

$moves = [];
$horse = new Horse(10, 10, 3, 3);

$iterations = 100;
$index = 0;
while ($index < $iterations) {
    while (true) {
        $result = $horse->move();
        if ($result === false) {
            echo 'Ilość ruchów: ' . count($horse->moves) . "\n";
            $c = count($moves);
            $cm = count($horse->moves);

            if ($c < $cm) {
                $moves = $horse->moves;
            }
            break;
        }
    }
    $index++;
}
$horse->drawTable();

//Horse::dump($horse->table);





