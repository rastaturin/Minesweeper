<?php

namespace Games;

class Minesweeper extends ConsoleGame {

    /**
     * @var Board
     */
    private $board;

    public function start()
    {
        $n = intval($this->reader->getLine('Enter width'));
        $m = intval($this->reader->getLine('Enter height'));
        $amount = intval($this->reader->getLine('Enter bomb amount'));
        $this->board = new Board($n, $m, $amount);
        $this->reader->output($this->board->render());
        while ($this->board->remain()) {
            $action = $this->reader->getLine("Enter action (o - open, m - mark)");
            $x = intval($this->reader->getLine("Enter X"));
            $y = intval($this->reader->getLine("Enter Y"));

            if ($action == 'o') {
                if (!$this->board->open($x, $y)) {
                    $this->reader->output($this->board->render());
                    $this->reader->output("You lose!");
                    return;
                }
            } elseif ($action == 'm') {
                $this->board->mark($x, $y);
            }
            $this->reader->output($this->board->render());
            $this->reader->output("{$this->board->remain()} remain to open.");

        }
        $this->reader->output("You won!");
    }

}

class Board {
    const FIELD_EMPTY_CLOSED = 'e';
    const FIELD_BOMB_CLOSED = 'b';
    const FIELD_EMPTY_MARKED = 'E';
    const FIELD_BOMB_MARKED = 'B';
    const FIELD_OPENED = ' ';
    const FIELD_BOMB = '!';

    private $board = [];
    private $amount = 0;
    private $closed = 0;

    /**
     * @param int $n width
     * @param int $m height
     * @param int $amount amount of bombs
     */
    public function __construct($n, $m, $amount)
    {
        $this->closed = $m * $n;

        if ($amount > $m * $n) {
            $amount = $m * $n;
        }

        for ($i = 0; $i < $m; $i++) {
            $this->board[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $this->board[$i][$j] = self::FIELD_EMPTY_CLOSED;
            }
        }

        while ($amount) {
            $i = rand(0, $m - 1);
            $j = rand(0, $n - 1);
            if ($this->board[$i][$j] == self::FIELD_EMPTY_CLOSED) {
                $this->board[$i][$j] = self::FIELD_BOMB_CLOSED;
                $amount--;
                $this->amount++;
            }
        }
    }

    /**
     * @return int
     */
    public function remain() {
        return $this->closed - $this->amount;
    }

    /**
     * @return array
     */
    public function render() {
        $result = [];
        $output = '   ';
        foreach ($this->board[0] as $j => $cell) {
            $output .= $j%10;
        }
        $result[] = $output;
        foreach ($this->board as $i => $row) {
            $output = $i < 10 ? " $i|" : "$i|";
            foreach ($row as $j => $cell) {
                $output .= $this->showCell($cell);
            }
            $result[] = $output;
        }
        return $result;
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool successful opened
     */
    public function open($x, $y) {
        if ($this->board[$y][$x] == self::FIELD_BOMB_CLOSED) {
            $this->board[$y][$x] = self::FIELD_BOMB;
            return false;
        }

        if ($this->board[$y][$x] == self::FIELD_EMPTY_CLOSED) {
            $this->expand($x, $y);
        }
        return true;
    }

    /**
     * @param int $x
     * @param int $y
     */
    private function expand($x, $y) {
        $this->closed--;
        $count = $this->calcNeibs($x, $y);
        if ($count > 0) {
            $this->board[$y][$x] = $count;
            return;
        }

        $this->board[$y][$x] = self::FIELD_OPENED;
        foreach ([$x - 1, $x, $x+1] as $x1) {
            foreach ([$y - 1, $y, $y+1] as $y1) {
                if ($this->within($x1, $y1) && $this->isClosed($x1, $y1) && ($x1 != $x || $y1 != $y)) {
                    $this->expand($x1, $y1);
                }
            }
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function within($x, $y) {
        return $x >= 0 && $x < count($this->board[0]) && $y >= 0 && $y < count($this->board);
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function isClosed($x, $y) {
        return in_array($this->board[$y][$x], [self::FIELD_EMPTY_CLOSED, self::FIELD_BOMB_CLOSED]);
    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function isBomb($x, $y) {
        return in_array($this->board[$y][$x], [self::FIELD_BOMB_CLOSED, self::FIELD_BOMB, self::FIELD_BOMB_MARKED]);
    }

    /**
     * Calculate number on the given location
     * @param int $x
     * @param int $y
     * @return int
     */
    private function calcNeibs($x, $y) {
        $count = 0;
        foreach ([$x - 1, $x, $x+1] as $x1) {
            foreach ([$y - 1, $y, $y+1] as $y1) {
                if ($this->within($x1, $y1) && $this->isBomb($x1, $y1) && ($x1 != $x || $y1 != $y)) {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * @param int $x
     * @param int $y
     */
    public function mark($x, $y) {
        if ($this->board[$y][$x] == self::FIELD_BOMB_CLOSED) {
            $this->board[$y][$x] = self::FIELD_BOMB_MARKED;
        }

        if ($this->board[$y][$x] == self::FIELD_EMPTY_CLOSED) {
            $this->board[$y][$x] = self::FIELD_EMPTY_MARKED;
        }
    }

    /**
     * @param mixed $cell
     * @return string
     */
    private function showCell($cell) {
        switch ($cell) {
            case self::FIELD_BOMB_CLOSED:
            case self::FIELD_EMPTY_CLOSED:
                return 'O';
            case self::FIELD_BOMB_MARKED:
            case self::FIELD_EMPTY_MARKED:
                return 'X';
            case self::FIELD_OPENED:
                return ' ';
            case self::FIELD_BOMB:
                return '!';
            default:
                return $cell;
        }

    }
}
