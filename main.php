<?php

require ('Readers.php');
require ('Games\ConsoleGame.php');
require ('Games\Minesweeper.php');

use Games\Minesweeper;


$commandReader = isset($argv[1]) ? new FileReader($argv[1]) : new Console();

$game = new Minesweeper($commandReader);
$game->start();
