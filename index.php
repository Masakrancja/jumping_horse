<?php
declare(strict_types=1);
namespace App;
require 'vendor/autoload.php';

use App\Horse;

$P = [8, 8]; //Canvas
$p = [0, 0]; // Start point
$moves = [];
$horse = new Horse($P[0], $P[1], $p[0], $p[1]);
$iterations = 10000;
$index = 0;
while ($index < $iterations) {
    while (true) {
        $result = $horse->move();
        if ($result === false) {
            $c = count($moves);
            $cm = count($horse->moves);
            if ($c < $cm) {
                $moves = $horse->moves;
            }
            break;
        }
    }
    $horse->setStartPoint($p[0], $p[1]);
    $index++;
}
$horse->fillTable($moves);
$horse->drawTable();
if ($horse->isCli()) {
    echo 'Ilość ruchów: ' . count($moves) . "\n";
} else {
    ?>
        <div style="text-align: center; font-size: 24px;">Ilość ruchów: <?php echo count($moves);?></div>
    <?php
}
