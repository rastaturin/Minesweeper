<?php

namespace Games;

use Reader;

abstract class ConsoleGame {
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    abstract public function start();
}
